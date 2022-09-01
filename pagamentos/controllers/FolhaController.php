<?php

/*
 * Conjunto de funções úteis a todas as classes
 */

namespace pagamentos\controllers;

use Yii;
use yii\helpers\Url;
use kartik\helpers\Html;
use pagamentos\models\Folha;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use DateTime;
use Exception;
use JasperServerIntegration;
use pagamentos\models\CadSfuncional;
use pagamentos\models\FinSfuncional;
use pagamentos\models\FinReferencias;
use pagamentos\models\FinParametros;
use pagamentos\models\FinEventos;
use PHPJasper\PHPJasper;
use yii\helpers\Json;

/**
 * Funções da geração da folha de pagamento
 *
 * @author TomMe
 */
class FolhaController extends \yii\web\Controller
{

    public $gestor = 0;
    public $folha = 0;

    const CLASS_VERB_NAME = 'Folha de pagamento';
    const CLASS_VERB_NAME_PL = 'Folhas de pagamento';
    const OPERACOES_DA_GERACAO_EXCL_INDIVIDUAL = 1;
    const OPERACOES_DA_GERACAO_INI_INDIVIDUAL = 6;
    const OPERACOES_DA_GERACAO = 11;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        if (!Yii::$app->user->isGuest) {
            $this->folha = Yii::$app->user->identity->folha;
            $this->gestor = Yii::$app->user->identity->gestor;
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return false;
                        }
                    ],
                    [
                        'actions' => ['z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->folha >= 1 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['c', 's-c', 'dpl', 'print'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->folha >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->folha >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->folha >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->session->setFlash('info', Yii::t('yii', 'Você tentou acessar uma página ou função inexistente!'));
                    return $this->goHome();
                }
            ],
        ];
    }

    public function actionIndex()
    {
    }

    public function actionView()
    {
    }

    /**
     * Imprime um recurso
     * @param type $format
     * @param type $f = filename
     * @return type
     */
    public function actionPrint($format = 'pdf', $f = null)
    {
        $model = new Folha(true);
        $model->titulo = $f;
        if ($model->load($post = Yii::$app->request->post())) {
            $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
            $jasper = new PHPJasper;
            // $jasper->pathExecutable = Yii::getAlias('@common/components/JasperStarter/bin');
            $options = [
                'format' => [$format],
                'locale' => 'pt_BR',
                'db_connection' => [
                    'driver' => 'mysql',
                    'username' => Yii::$app->db->rpt_username,
                    'password' => Yii::$app->db->rpt_password,
                    'host' => Yii::$app->db->host,
                    'database' => 'mgfolha_' . str_replace("_", "", $cliente),
                    'port' => Yii::$app->db->port,
                    //'jdbc_dir' => Yii::getAlias('@vendor/geekcom/phpjasper/bin/jasperstarter/jdbc'),
                ]
            ];
            // declara aqui o relatório solicitado e desencadea a operação
            $model->titulo = $f;
            switch ($model->titulo) {
                case 'resumoGeral':
                    $model->id_cad_servidores = 'cs.id ' . (empty(trim($model->id_cad_servidores)) ? "IS NOT NULL" : 'in (' . $model->id_cad_servidores . ')');
                    $model->id_cad_cargos = 'ff.id_cad_cargos ' . (empty(($model->id_cad_cargos)) ? "IS NOT NULL" : "in (" . implode(",", $model->id_cad_cargos) . ")");
                    $model->id_cad_departamentos = 'ff.id_cad_departamentos ' . (empty(($model->id_cad_departamentos)) ? "IS NOT NULL" : "in (" . implode(",", $model->id_cad_departamentos) . ")");
                    $model->id_cad_centros = 'ff.id_cad_centros ' . (empty(($model->id_cad_centros)) ? "IS NOT NULL" : "in (" . implode(",", $model->id_cad_centros) . ")");
                    $model->id_cad_locais_trabalho = 'cf.id_local_trabalho ' . (empty(($model->id_cad_locais_trabalho)) ? "IS NOT NULL" : "in (" . implode(",", $model->id_cad_locais_trabalho) . ")");
                    return $this->printResumoGeral($model, $format);
                    //                    return \yii\helpers\Json::encode($model);
                    break;
            }
        } else if ($f == null || strlen($format) == 0) {
            Yii::$app->session->setFlash('info', Yii::t('yii', 'Faltou informar o arquivo a imprimir ou o formato desejado na saída!'));
            return $this->goHome();
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Retorna o(s) holerite(s) solicitado(s) para impressão
     */
    public function printResumoGeral($model, $format = 'pdf')
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        $db = Yii::$app->$db->dbname;
        $fileName = 'resumoGeral';
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $parcelaReport = substr(Yii::$app->user->identity->per_parcela, 1);

        $controls = array(
            'titulo' => str_replace(' ', '%20', $this->getImpressoes($fileName)['titulo']),
            'descricao' => str_replace(' ', '%20', "Folha: $mes/$ano" . ($parcela != '000' ? " | Folha complementar: $parcelaReport" : '')),
            'ano' => "ff.ano='$ano'",
            'mes' => "ff.mes='$mes'",
            'parcela' => "ff.parcela='$parcela'",
            'dominio' => str_replace(' ', '%20', $model->dominio),
            'tableSchema' => $db,
            'id_usuario' => Yii::$app->user->identity->id,
            'id_cad_servidores' => str_replace(' ', '%20', $model->id_cad_servidores),
            'id_cad_centros' => str_replace(' ', '%20', $model->id_cad_centros),
            'id_cad_departamentos' => str_replace(' ', '%20', $model->id_cad_departamentos),
            'id_cad_cargos' => str_replace(' ', '%20', $model->id_cad_cargos),
            'id_cad_locais_trabalho' => str_replace(' ', '%20', $model->id_cad_locais_trabalho),
        );
        require_once(Yii::getAlias("@common/components/jasperserverintegration.php"));
        // Cria o objeto do JasperServerIntegration
        $jsi = new JasperServerIntegration(
            'http://ns1.mgcash.app.br:8080/jasperserver',     // URL do JasperServer
            "reports/MGFolha/folha/$fileName",     // Caminho do relatório no JasperServer sem a primeira barra
            $format,                                        // Tipo da exportação do relatório
            'caixa',                                  // Usuário com acesso ao relatório
            'C@1xa',                                   // Senha do usuário
            $controls                                       // Array com os parâmetros (opcional)
        );

        // Executa o relatório
        try {
            $data = $jsi->execute();
            SisEventsController::registrarEvento("Resumo geral da folha impresso com sucesso!", 'printResumoGeral', Yii::$app->user->identity->id, 'folha', $id);
            header('Content-Type: application/pdf');
            echo $data;
            // print_r($jsi);
        } catch (Exception $e) {
            $eMsg = $e->getMessage();
            $eCode = $e->getCode();
            print("Erro - $eCode: $eMsg.");
        }
    }

    /**
     * Retorna o resumo geral solicitado para impressão
     */
    // public function printResumoGeral($jasper, $options, $model, $format = 'pdf')
    // {
    //     //        return \yii\helpers\Json::encode($model);
    //     //        $model = new Folha();
    //     $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
    //     $dominio = $model->dominio;
    //     $ano = $model->ano;
    //     $mes = $model->mes;
    //     $parcela = $model->parcela;
    //     $f = 'resumoGeral';
    //     $jasperExtension = '.jasper';
    //     $jrxmlExtension = '.jrxml';
    //     $outputExtension = ".$format";
    //     $fileRoot = Yii::getAlias('@pagamentos/reports/folha/');
    //     $rptsSaveRoot = Yii::getAlias("@uploads_root/../reports/$cliente/$dominio/folha/$ano/$mes/$parcela");
    //     if (!file_exists($rptsSaveRoot)) {
    //         mkdir($rptsSaveRoot, 0755, true);
    //     }
    //     setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    //     date_default_timezone_set("America/Maceio");
    //     $reportParams = ['params' => [
    //         'titulo' => $this->getImpressoes($f)['titulo'],
    //         'descricao' => "Folha: $ano/$mes/$parcela",
    //         'ano' => "ff.ano = '$ano'",
    //         'mes' => "ff.mes = '$mes'",
    //         'parcela' => "ff.parcela = '$parcela'",
    //         'dominio' => $model->dominio,
    //         'id_usuario' => Yii::$app->user->identity->id,
    //         'id_cad_servidores' => $model->id_cad_servidores,
    //         'id_cad_centros' => $model->id_cad_centros,
    //         'id_cad_departamentos' => $model->id_cad_departamentos,
    //         'id_cad_cargos' => $model->id_cad_cargos,
    //         'id_cad_locais_trabalho' => $model->id_cad_locais_trabalho,
    //         'SUBREPORT_DIR' => $fileRoot . '../',
    //     ]];
    //     $optionsFinal = \yii\helpers\ArrayHelper::merge($options, $reportParams);
    //     //    return $jasper->process($fileRoot . $f . $jasperExtension, $rptsSaveRoot, $optionsFinal)->output(); 
    //     $jasper->process($fileRoot . $f . $jasperExtension, $rptsSaveRoot, $optionsFinal)->execute(); //->output(); 
    //     SisEventsController::registrarEvento("$model->titulo gerado com sucesso", 'printResumoGeral', Yii::$app->user->identity->id, $model->tableName(), null);
    //     return Yii::$app->response->sendFile($rptsSaveRoot . '/' . $f . $outputExtension, $f . '_' . $ano . $mes . $parcela . $outputExtension, ['inline' => true]);
    // }

    /**
     * Creates a new Folha model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($o = 0)
    {
        $mess = null;
        do {
            $mess .= $this->actionSC($o)['mess'] . '<br>';
            $o++;
        } while ($o <= self::OPERACOES_DA_GERACAO);
        Yii::$app->session->setFlash('success', Yii::t('yii', $mess));
        return $this->goHome();
    }

    /**
     * Setar o DB baseado na URL
     * @return type
     */
    public function getDb()
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        return Yii::$app->$db->dbname;
    }

    /**
     * Retorna a operação a executar
     * Operações 0 a 4 alteram toda a folha do mês
     * Operações 5... podem alterar apenas um servidor
     * @param type $oper
     * @return string
     */
    public function getOper($oper, $id_cad_servidores = 0)
    {
        switch ($oper) {
                // fecha as folhas abertas exceto 13º
            case 0:
                $return = ['oper' => $this->sqlFechaFolhas(), 'mess' => 'Fechamento das folhas anteriores'];
                break;
                // exclui fin_rubricas do periodo e dominio
            case 1:
                $return = ['oper' => $this->sqlFinRubricasDel($id_cad_servidores), 'mess' => 'Tabela de rubricas preparada'];
                break;
                // exclui cad_sfuncional do periodo e dominio
            case 2:
                $return = ['oper' => $this->sqlCadSFuncionalDel($id_cad_servidores), 'mess' => 'Tabela funcional preparada'];
                break;
                // geração dos registros em cad_sfuncional do periodo e dominio
            case 3:
                $return = ['oper' => $this->sqlCadSFuncional($id_cad_servidores), 'mess' => 'Dados funcionais armazenados'];
                break;
                // exclui fin_sfuncional do periodo e dominio
            case 4:
                $return = ['oper' => $this->sqlFinSFuncionalDel($id_cad_servidores), 'mess' => 'Tabela financeira preparada'];
                break;
                // geração dos registros em fin_sfuncional do periodo e dominio
            case 5:
                $return = ['oper' => $this->sqlFinSFuncional($id_cad_servidores), 'mess' => 'Dados financeiros inseridos'];
                break;
                // geração dos registros fin_folha_eventos em fin_rubricas do periodo e dominio
                // Este procedimento gera as rúbricas básicas (001 e 002)da folha
            case 6:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Rúbricas básicas inseridas'];
                break;
                // geração dos registros fin_folha_eventos_recorrentes em fin_rubricas do periodo e dominio
            case 7:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos_recorrentes(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Rúbricas recorrentes inseridas'];
                break;
                // geração dos registros fin_folha_eventos_parcelados em fin_rubricas do periodo e dominio
            case 8:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos_parcelados(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Rúbricas parceladas inseridas'];
                break;
                // geração dos registros fin_folha_eventos_consignados em fin_rubricas do periodo e dominio
            case 9:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos_consignados(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Rúbricas consignadas inseridas'];
                break;
                // geração dos registros fin_folha_eventos_enios em fin_rubricas do periodo e dominio
            case 10:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos_enios(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Rúbricas de ênios inseridas'];
                break;
                // geração dos registros fin_folha_eventos_salario_familia em fin_rubricas do periodo e dominio
            case 11:
                $return = ['oper' => "CALL " . $this->getDb() . ".fin_folha_eventos_root(:id_user,:dominio,:ano,:mes,:parcela,:id_cad_servidor)", 'mess' => 'Salário Família inserido'];
                break;
            default:
                '';
                break;
        }
        return $return;
    }

    /**
     * Retorna algumas tags de dados aprimorados para a impressao a executar a 
     * partir do nome do arquivo .jasper a imprimir
     * @return string
     */
    public static function getImpressoes($f)
    {
        switch ($f) {
            case 'resumoGeral':
                $return = ['filename' => $f, 'titulo' => 'Resumo geral da folha de pagamento'];
                break;
        }
        return $return;
    }

    /**
     * Updates an existing Folha model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false)
    {
        return true;
    }

    /**
     * Deletes an existing Folha model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id)
    {
        return true;
    }

    /**
     * Duplicates an existing Folha model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id)
    {
        return true;
    }

    public static function setAlterFolhaServidor($oI, $id_cad_servidor)
    {
        $objInst = new FolhaController(null, null);
        $ret = '';
        $data = $objInst->actionSC(self::OPERACOES_DA_GERACAO_EXCL_INDIVIDUAL, $id_cad_servidor);
        for ($i = $oI; $i <= self::OPERACOES_DA_GERACAO; $i++) {
            $data = $objInst->actionSC($i, $id_cad_servidor);
            $ret .= ($i > $oI ? ', ' : '') . $data['mess'];
        }
        $ret = strtolower(str_replace('inseridas', 'editadas', $ret));
        $ret = strtoupper(substr($ret, 0, 1)) . substr($ret, 1);
        return $ret;
    }

    /**
     * Executa as operçãoes de geração da folha direto no BD
     * @param type $oI Operação inicial
     * @param type $id_cad_servidor ID do servidor
     * @return string
     * @throws \Exception
     */
    public function actionSC($oI, $id_cad_servidor = 0)
    {
        $id_user = Yii::$app->user->identity->id;
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $model = new Folha();
        $oper = $this->getOper($oI, $id_cad_servidor);
        $data = ['mess' => Yii::t('yii', $oper['mess']), 'class' => 'success', 'stat' => true];
        set_time_limit(360);
        ini_set('memory_limit', '512M');
        $connection = Yii::$app->db;
        $transac = $connection->beginTransaction();
        try {
            $connection->createCommand($oper['oper'])
                ->bindValue(':id_user', $id_user)
                ->bindValue(':dominio', $dominio)
                ->bindValue(':ano', $ano)
                ->bindValue(':mes', $mes)
                ->bindValue(':parcela', $parcela)
                ->bindValue(':id_cad_servidor', $id_cad_servidor)
                ->execute();
            $transac->commit();
        } catch (\Exception $e) {
            $data = 'Exception: ' . $e->getMessage();
            $transac->rollBack();
            $data = ['mess' => Yii::t('yii', 'Tentativa de geração de folha sem sucesso. Exception. Erro(s): {error}', [
                'error' => $data
            ]), 'class' => 'warning'];
            throw $e;
        } catch (\Throwable $e) {
            $data = 'Throwable: ' . $e->getMessage();
            $transac->rollBack();
            $data = ['mess' => Yii::t('yii', 'Tentativa de geração de folha sem sucesso. Throwable. Erro(s): {error}', [
                'error' => $data
            ]), 'class' => 'warning'];
            throw $e;
        }
        //        gera evento para ser gravado no log do sistema
        SisEventsController::registrarEvento($data['mess'], 'actionSC', Yii::$app->user->identity->id, $model->tableName());
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Monta e retorna o SQL para geração dos registros em CadSFuncional
     * @return type
     */
    public function sqlCadSFuncional($id_cad_servidores = null)
    {
        $model = new Folha();
        $status = CadSfuncional::STATUS_ATIVO;
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $finParametros = FinParametros::find()
            ->join(
                'join',
                $this->getDb() . ".cad_sfuncional",
                'cast(cad_sfuncional.ano as unsigned) <= cast(fin_parametros.ano_informacao as unsigned) '
                    . 'and cast(cad_sfuncional.mes as unsigned) <= cast(fin_parametros.mes_informacao as unsigned) '
                    . 'and cast(cad_sfuncional.parcela as unsigned) <= cast(fin_parametros.parcela_informacao as unsigned)'
            )
            ->where([
                'fin_parametros.dominio' => $dominio, 'fin_parametros.ano' => $ano,
                'fin_parametros.mes' => $mes, 'fin_parametros.parcela' => $parcela,
            ])->one();
        if (($finParametros) == null) {
            $finParametros = CadSfuncional::find()
                ->where([
                    'dominio' => $dominio,
                ])
                ->groupBy('ano, mes, parcela')
                ->orderBy('ano DESC, mes DESC, parcela')
                ->limit(1)->one();
            $anoI = $finParametros->ano;
            $mesI = $finParametros->mes;
            $parcelaI = $finParametros->parcela;
        } else {
            $anoI = $finParametros->ano_informacao;
            $mesI = $finParametros->mes_informacao;
            $parcelaI = $finParametros->parcela_informacao;
        }
        $folha = $ano . '/' . $mes . '/' . $parcela;
        $evento = SisEventsController::registrarEvento(
            "Registro funcional criado na geração "
                . "da folha de pagamento $folha!",
            'actionC',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "INSERT INTO " . $this->getDb() . ".cad_sfuncional ("
            . "id,slug,status,dominio,evento,created_at,updated_at,id_cad_servidores,"
            . "ano,mes,parcela,id_local_trabalho,id_cad_principal,id_escolaridade,"
            . "escolaridaderais,rais,dirf,sefip,sicap,insalubridade,decimo,"
            . "id_vinculo,id_cat_sefip,ocorrencia,
                carga_horaria,molestia,"
            . "d_laudomolestia,manad_tiponomeacao,manad_numeronomeacao,"
            . "d_tempo,d_tempofim,d_beneficio,n_valorbaseinss) ("
            . "SELECT NULL,SHA(CONCAT(id, slug)),$status,dominio,$evento,"
            . "UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),id_cad_servidores,"
            . "'$ano','$mes','$parcela',id_local_trabalho,id_cad_principal,"
            . "id_escolaridade,escolaridaderais,rais,dirf,sefip,sicap,"
            . "insalubridade,decimo,id_vinculo,id_cat_sefip,ocorrencia,"
            . "carga_horaria,molestia,d_laudomolestia,manad_tiponomeacao,"
            . "manad_numeronomeacao,d_tempo,d_tempofim,d_beneficio,"
            . "n_valorbaseinss FROM " . $this->getDb() . ".cad_sfuncional 
                where CAST(ano AS UNSIGNED) <= '$anoI' "
            . "and CAST(mes AS UNSIGNED) <= '$mesI' and CAST(parcela AS UNSIGNED) <= '$parcelaI' and dominio = '$dominio'"
            . ($id_cad_servidores != null ? " and id_cad_servidores = $id_cad_servidores" : "") . ")";
    }

    /**
     * Monta e retorna o SQL para fechamento de todas as outras folhas
     * exceto 13º(todas as parcelas do ano) e folha atual
     * @return type
     */
    public function sqlFechaFolhas()
    {
        $model = new Folha();
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        SisEventsController::registrarEvento(
            "Fechamento de todas as folhas!",
            'FechaFolhas',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "UPDATE " . $this->getDb() . ".fin_parametros SET situacao = 0 "
            . "WHERE dominio = '$dominio' and (!(ano = $ano and mes = '13') "
            . "and !(ano = '$ano' and mes = '$mes' and parcela = '$parcela'))";
    }

    /**
     * Monta e retorna o SQL para exclusão dos registros em FinRubricas
     * @return type
     */
    public function sqlFinRubricasDel($id_cad_servidores = null)
    {
        $model = new Folha();
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $folha = $ano . '/' . $mes . '/' . $parcela;
        SisEventsController::registrarEvento(
            "Rubricas da folha de pagamento "
                . "$folha excluídos antes da nova geração!",
            'FinRubricasDel',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "DELETE FROM " . $this->getDb() . ".fin_rubricas where ano = '$ano' "
            . "and mes = '$mes' and parcela = '$parcela' and dominio = '$dominio'"
            . ($id_cad_servidores > 0 ? " and id_cad_servidores = $id_cad_servidores" : "");
    }

    /**
     * Monta e retorna o SQL para exclusão dos registros em CadSFuncional
     * @return type
     */
    public function sqlCadSFuncionalDel($id_cad_servidores = null)
    {
        $model = new Folha();
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $folha = $ano . '/' . $mes . '/' . $parcela;
        SisEventsController::registrarEvento(
            "Registros funcionais da folha de pagamento "
                . "$folha excluídos antes da nova geração!",
            'CadSFuncionalDel',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "DELETE FROM " . $this->getDb() . ".cad_sfuncional where ano = '$ano' "
            . "and mes = '$mes' and parcela = '$parcela' and dominio = '$dominio'"
            . ($id_cad_servidores != null ? " and id_cad_servidores = $id_cad_servidores" : "");
    }

    /**
     * Monta e retorna o SQL para geração dos registros em FinSFuncional
     * @return type
     */
    public function sqlFinSFuncional($id_cad_servidores = null)
    {
        $model = new Folha();
        $status = CadSfuncional::STATUS_ATIVO;
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $finParametros = FinParametros::find()
            ->where([
                'dominio' => $dominio, 'ano' => $ano,
                'mes' => $mes, 'parcela' => $parcela,
            ])->one();
        $anoI = $finParametros->ano_informacao;
        $mesI = $finParametros->mes_informacao;
        $parcelaI = $finParametros->parcela_informacao;
        $folha = $ano . '/' . $mes . '/' . $parcela;
        $evento = SisEventsController::registrarEvento(
            "Registro financeiro criado na geração "
                . "da folha de pagamento $folha!",
            'actionC',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "INSERT INTO " . $this->getDb() . ".fin_sfuncional (id,slug,STATUS,dominio,created_at,"
            . "updated_at,id_cad_servidores,evento,ano,mes,parcela,situacao,"
            . "situacaofuncional,id_cad_cargos,id_cad_centros,id_cad_departamentos,"
            . "id_pccs,desconta_irrf,tp_previdencia," //desconta_inss,desconta_rpps,"
            . "desconta_sindicato,lanca_anuenio,lanca_trienio,lanca_quinquenio,"
            . "lanca_decenio,enio,lanca_salario,lanca_funcao,n_faltas,decimo_aniv,"
            . "n_horaaula,categoria_receita,previdencia,tipobeneficio,"
            . "d_beneficio,retorno_ocorrencia,retorno_data,retorno_valor,"
            . "retorno_documento, ponto)(SELECT NULL,SHA(CONCAT(id, UNIX_TIMESTAMP(NOW()), slug)),$status,"
            . "dominio,UNIX_TIMESTAMP(NOW()),UNIX_TIMESTAMP(NOW()),"
            . "id_cad_servidores,$evento,'$ano','$mes','$parcela',situacao,"
            . "situacaofuncional,id_cad_cargos,id_cad_centros,id_cad_departamentos,"
            . "id_pccs,desconta_irrf,tp_previdencia," //desconta_inss,desconta_rpps,"
            . "desconta_sindicato,lanca_anuenio,lanca_trienio,lanca_quinquenio,"
            . "lanca_decenio,enio,lanca_salario,lanca_funcao,n_faltas,decimo_aniv,"
            . "n_horaaula,categoria_receita,previdencia,tipobeneficio,"
            . "d_beneficio,retorno_ocorrencia,retorno_data,retorno_valor,"
            . "retorno_documento,ponto FROM " . $this->getDb() . ".fin_sfuncional WHERE ano = '$anoI' "
            . "and mes = '$mesI' and parcela = '$parcelaI' and dominio = '$dominio'"
            . ($id_cad_servidores != null ? " and id_cad_servidores = $id_cad_servidores" : "") . ")";
    }

    /**
     * Monta e retorna o SQL para exclusão dos registros em CadSFuncional
     * @return type
     */
    public function sqlFinSFuncionalDel($id_cad_servidores = null)
    {
        $model = new Folha();
        $dominio = Yii::$app->user->identity->dominio;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $folha = $ano . '/' . $mes . '/' . $parcela;
        SisEventsController::registrarEvento(
            "Registros financeiros da folha de pagamento "
                . "$folha excluídos antes da nova geração!",
            'FinSFuncionalDel',
            Yii::$app->user->identity->id,
            $model->tableName(),
            0
        );
        return "DELETE FROM " . $this->getDb() . ".fin_sfuncional where ano = '$ano' "
            . "and mes = '$mes' and parcela = '$parcela' and dominio = '$dominio'"
            . ($id_cad_servidores != null ? " and id_cad_servidores = $id_cad_servidores" : "");
    }

    public static function getFinParametro()
    {
        $loc = FinParametros::tableName();
        $model = FinParametros::find()
            ->where([
                $loc . '.dominio' => Yii::$app->user->identity->dominio,
                $loc . '.ano' => Yii::$app->user->identity->per_ano,
                $loc . '.mes' => Yii::$app->user->identity->per_mes,
                $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            ])->one();
        return $model;
    }

    /**
     * retorna os funcionais do dominio e período
     * @param type $id_cad_servidores
     * @return type
     */
    public function getCadSFuncional()
    {
        $loc = CadSfuncional::tableName();
        return CadSfuncional::find()
            ->where([
                $loc . '.dominio' => Yii::$app->user->identity->dominio,
                $loc . '.ano' => Yii::$app->user->identity->per_ano,
                $loc . '.mes' => Yii::$app->user->identity->per_mes,
                $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            ])
            //                        ->limit(1000)
            ->all();
    }

    /**
     * retorna o funcional do servidor
     * @param type $id_cad_servidores
     * @return type
     */
    public function getFinSFuncional($id_cad_servidores)
    {
        $loc = FinSfuncional::tableName();
        return FinSfuncional::find()
            ->where([
                $loc . '.dominio' => Yii::$app->user->identity->dominio,
                $loc . '.ano' => Yii::$app->user->identity->per_ano,
                $loc . '.mes' => Yii::$app->user->identity->per_mes,
                $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
                $loc . '.id_cad_servidores' => $id_cad_servidores,
            ])
            ->one();
    }

    /**
     * retorna uma lista contendo as referencias de classe
     * @param type $id_pccs
     * @return type
     */
    public function getFinReferencias($id_pccs)
    {
        $dominio = Yii::$app->user->identity->dominio;
        $loc = FinReferencias::tableName();
        return FinReferencias::find()
            ->select("$loc.id_pccs,$loc.id_classe,$loc.referencia,"
                . "$loc.valor,$loc.data,cad_classes.i_ano_inicial,cad_classes.i_ano_final")
            ->join('join', $this->getDb() . '.cad_classes', "cad_classes.id_pccs = fin_referencias.id_pccs "
                . "and fin_referencias.id_classe = cad_classes.id "
                . "and cad_classes.dominio = '$dominio'")
            ->join('join', $this->getDb() . '.cad_pccs', "cad_pccs.id = fin_referencias.id_pccs "
                . "and cad_pccs.dominio = '$dominio'")
            ->where([
                "$loc.dominio" => $dominio,
                "cad_pccs.id_pccs" => $id_pccs,
            ])
            ->andWhere("$loc.data <= DATE_FORMAT(CURRENT_DATE, '%d-%m-%Y')")
            ->orderBy("$loc.data DESC, $loc.referencia")
            ->all();
    }

    /**
     * Retorna a diferença entre datas e retorna em anos
     * Se a data final não for informada então a data de hoje será utilizada
     * @param type $data_inicial = formato da data yyyy-mm-dd
     * @param type $data_final = formato da data yyyy-mm-dd
     * @return type
     */
    public function calc_dif_data($data_inicial, $data_final = null)
    {
        $date = new DateTime($data_inicial);
        $interval = $date->diff(new DateTime(($data_final != null ? $data_final : date("Y-m-d"))));
        return [
            'a' => $interval->format('%Y'),
            'm' => $interval->format('%m'),
            'd' => $interval->format('%d'),
        ];
    }

    public function getFinEvento($rubrica)
    {
        $loc = FinEventos::tableName();
        return FinEventos::find()
            ->where([
                $loc . '.dominio' => Yii::$app->user->identity->dominio,
                $loc . '.id_evento' => $rubrica,
            ])
            ->one();
    }

    public function actionZ()
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('erros', [
                'validacao' => self::getValidacaoFolha(),
            ]);
        } else {
            return $this->render('erros', [
                'validacao' => self::getValidacaoFolha(),
            ]);
        }
    }

    /**
     * Valida a folha
     * @return boolean
     */
    public static function getValidaFolha()
    {
        $retorno = count(self::getSituacoesfuncionais()) +
            count(self::getSituacoes()) +
            count(self::getId_cad_cargos()) +
            count(self::getId_cad_departamentos()) +
            count(self::getId_cad_centros()) +
            count(self::getId_pcc());
        return $retorno;
    }

    /**
     * Validações diversas da folha
     * @return boolean
     */
    public static function getValidacaoFolha()
    {
        $retorno = null;
        $y = 0;
        $cv = null;
        $matriculas = [];
        $finSitFunc = self::getSituacoesfuncionais();
        $finSit = self::getSituacoes();
        $finCadCargos = self::getId_cad_cargos();
        $finCadCentros = self::getId_cad_centros();
        $finCadDepartamentos = self::getId_cad_departamentos();
        $finCadPCC = self::getId_pcc();
        foreach ($finCadPCC as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'id_pccs'];
        }
        foreach ($finCadDepartamentos as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'id_cad_departamentos'];
        }
        foreach ($finCadCentros as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'id_cad_centros'];
        }
        foreach ($finCadCargos as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'id_cad_cargos'];
        }
        foreach ($finSitFunc as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'situacaofuncional'];
        }
        foreach ($finSit as $array) {
            $matriculas[] = ['mat' => $array->getCadServidor()->matricula, 'err' => 'situacao'];
        }
        $mats = [];
        // agrupo as matriculas
        foreach ($matriculas as $key => $matricula) {
            if (!in_array($matricula['mat'], $mats)) {
                $mats[] = $matricula['mat'];
                $y++;
            }
        }
        // ordeno as matriculas
        sort($mats);
        foreach ($mats as $mat) {
            $errs = null;
            foreach ($matriculas as $key => $matricula) {
                if ($mat == $matricula['mat']) {
                    $errs = $errs . ',' . $matricula['err'];
                }
            }
            $resultadoordenado[] = ['mat' => $mat, 'err' => substr($errs, 1)];
        }
        foreach ($resultadoordenado as $key => $value) {
            $mat = $value['mat'];
            $err = $value['err'];
            $retorno .= Html::a($mat . ' | ', Url::to(["fin-sfuncional/$mat?mv=edit&cv=$err"]));
        }
        $retorno = "Há um ou mais servidores ativos faltando informações. Favor verificar: <br>$retorno";
        return $retorno;
    }

    /**
     * Retorna os servidores faltando situacaofuncional
     * @return type
     */
    public static function getSituacoesfuncionais()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            $loc . '.situacao' => FinSfuncional::SITUACAO_ATIVO,
        ])
            ->andWhere('situacaofuncional is null')
            ->all();
        return $model;
    }

    /**
     * Retorna os servidores faltando situacao
     * @return type
     */
    public static function getSituacoes()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
        ])
            ->andWhere('situacao is null')
            ->all();
        return $model;
    }

    /**
     * Retorna os servidores faltando id_cad_cargos
     * @return type
     */
    public static function getId_cad_cargos()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            $loc . '.situacao' => FinSfuncional::SITUACAO_ATIVO,
        ])
            ->andWhere('id_cad_cargos is null')
            ->all();
        return $model;
    }

    /**
     * Retorna os servidores faltando id_cad_centros
     * @return type
     */
    public static function getId_cad_centros()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            $loc . '.situacao' => FinSfuncional::SITUACAO_ATIVO,
        ])
            ->andWhere('id_cad_centros is null')
            ->all();
        return $model;
    }

    /**
     * Retorna os servidores faltando id_cad_departamentos
     * @return type
     */
    public static function getId_cad_departamentos()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            $loc . '.situacao' => FinSfuncional::SITUACAO_ATIVO,
        ])
            ->andWhere('id_cad_departamentos is null')
            ->all();
        return $model;
    }

    /**
     * Retorna os servidores faltando id_pccs
     * @return type
     */
    public static function getId_pcc()
    {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()->where([
            $loc . '.dominio' => Yii::$app->user->identity->dominio,
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
            $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
            $loc . '.situacao' => FinSfuncional::SITUACAO_ATIVO,
        ])
            ->andWhere('!(id_pccs > 0)')
            ->all();
        return $model;
    }
}
