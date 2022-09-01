<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\CadServidores;
use pagamentos\models\CadServidoresSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use common\models\UploadForm;
use yii\web\UploadedFile;
use kartik\detail\DetailView;
use kartik\helpers\Html;
use common\controllers\ImgController;
use PHPJasper\PHPJasper;
use common\controllers\AppController;
use Exception;
use JasperServerIntegration;
use pagamentos\models\Folha;
use Jaspersoft\Client\Client;
use Jaspersoft\Exception\RESTRequestException;
use pagamentos\models\CadSrecadastro;

/**
 * CadServidoresController implements the CRUD actions for CadServidores model.
 */
class CadServidoresController extends Controller
{

    public $gestor = 0;
    public $cadastros = 0;

    const CLASS_VERB_NAME = 'Servidor';
    const CLASS_VERB_NAME_PL = 'Servidores';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        if (!Yii::$app->user->isGuest) {
            $this->cadastros = Yii::$app->user->identity->cadastros;
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
                        'actions' => ['index', 'view', 'print', 'z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    [
                        'actions' => ['c', 'a-c', 'r', 'dpl', 'photo', 'upload'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt', 'script'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadastros >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CadServidores models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->identity->cadastros >= 1) {
            $searchModel = new CadServidoresSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if ($dataProvider->getCount() == 1) {
                $newQuery = clone $dataProvider->query;
                $model = $newQuery->limit(1)->one();

                return $this->redirect(["/cad-servidores/$model->slug"]);
            } else {
                return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Seu perfil não dá acesso a esta função: ' . substr(basename($this->className()), 0, strpos(basename($this->className()), 'Controller')));
            return $this->goHome();
        }
    }
    /**
     * Lists all CadServidores models.
     * @return mixed
     */
    public function actionScript()
    {
        if (Yii::$app->user->identity->cadastros >= 4 && Yii::$app->user->identity->administrador >= 2) {
            return $this->renderPartial('script', [
                // 'models' => CadServidores::findAll(['status' => 10])
                'models' => CadSrecadastro::find()
                    ->select("cad_srecadastro.id_cad_servidores, cad_srecadastro.created_at, cad_srecadastro.id_user_recadastro")
                    ->groupBy("cad_srecadastro.id_cad_servidores")
                    ->orderBy("cad_srecadastro.id_cad_servidores, cad_srecadastro.created_at desc")->all()
            ]);
        } else {
            Yii::$app->session->setFlash('warning', 'Seu perfil não dá acesso a esta função: ' . substr(basename($this->className()), 0, strpos(basename($this->className()), 'Controller')));
            return $this->goHome();
        }
    }

    /**
     * Displays a single CadServidores model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null, $tp = null)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->identity->cadastros >= 1) {
            $model_first = $this->findModel($id, true);
            $post = Yii::$app->request->post();
            $view = 'view';
            switch ($tp) {
                case 1:
                    $view = 'v_banco';
                    break;
                case 2:
                    $view = 'v_endereco';
                    break;
                case 3:
                    $view = 'v_documentos';
                    break;
            }
            if ($tp > 0) {
                $mv = DetailView::MODE_EDIT;
            }
            if ($post && $model->load($post) && $model->save()) {
                $model_last = $this->findModel($id, true);
                //          registra evento no log do sistema
                $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
                return $this->redirect(Url::home(true) . str_replace('_', '-', $model->tableName()) . '/' . $model->slug);
            } else {
                if (!Yii::$app->user && !($post && $model->load($post))) {
                    $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                    SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
                }
                if ($post && $model->load($post) && !$model->save()) {
                    Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir.<br>Erros: {erros}', ['erros' => AppController::limpa_json(Json::encode($model->getFirstErrors()))]));
                    return $this->render('view', [
                        'model' => $model,
                        'modal' => 0,
                    ]);
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax($view, [
                        'model' => $model,
                        'modal' => $modal,
                        'mv' => $mv,
                    ]);
                } else {
                    return $this->render($view, [
                        'model' => $model,
                        'modal' => $modal,
                        'mv' => $mv,
                    ]);
                }
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Seu perfil não dá acesso a esta função: ' . substr(basename($this->className()), 0, strpos(basename($this->className()), 'Controller')));
            return $this->goHome();
        }
    }

    /**
     * Creates a new CadServidores model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false)
    {
        $model = new CadServidores();
        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->actionAC($model->id)) {
            //          gera evento para ser gravado no log do sistema 
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();

            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modal' => $modal,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modal' => $modal,
            ]);
        }
    }

    /**
     * Cria um registro do servidor nas tabelas CadSFuncional e FinSFuncional
     * tendo como base o período atual do usuário
     * @param type $id Enviar o id do registro original
     * @param type $id_novo Enviar o id do registro duplicado
     * @return boolean
     */
    public function actionAC($id, $id_novo = null, $decimo = false)
    {
        $return = true;
        $model = $this->findModel($id);
        if ($id_novo == null) {
            $id_novo = $model->id;
        }
        // Verifica a existência e se não existir cria registro CadSfuncional 
        // duplicado do último existente
        if (($funcional = \pagamentos\models\CadSfuncional::find()->where([
            'id_cad_servidores' => $id_novo,
            'dominio' => $model->dominio,
            'ano' => Yii::$app->user->identity->per_ano,
            'mes' => $decimo ? '13' : Yii::$app->user->identity->per_mes,
            'parcela' => Yii::$app->user->identity->per_parcela,
        ])->one()) == null) {
            $cadsf = CadSfuncionalController::actionSC($model->id, $id_novo, $decimo);
            if (!$cadsf['class'] == 'success') {
                $return = false;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', $cadsf['mess']));
            }
        }
        // Verifica a existência e se não existir cria registro FinSfuncional 
        // duplicado do último existente
        if (($financeiro = \pagamentos\models\FinSfuncional::find()->where([
            'id_cad_servidores' => $id_novo,
            'dominio' => $model->dominio,
            'ano' => Yii::$app->user->identity->per_ano,
            'mes' => $decimo ? '13' : Yii::$app->user->identity->per_mes,
            'parcela' => Yii::$app->user->identity->per_parcela,
        ])->one()) == null) {
            $finsf = FinSfuncionalController::actionSC($model->id, $id_novo, $decimo);
            if (!$finsf['class'] == 'success') {
                $return = false;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', $finsf['mess']));
            }
        }
        return $return;
    }

    /**
     * Updates an existing CadServidores model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false)
    {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
            //          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            $data = Yii::t('yii', 'Successfully updated record.');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $model->slug]);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modal' => $modal,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'modal' => $modal,
            ]);
        }
    }

    /**
     * Deletes an existing CadServidores model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id)
    {
        $model = $this->findModel($id);
        //        $this->findModel($id)->delete();

        $model->status = CadServidores::STATUS_CANCELADO;
        $model->dominio = $model->dominio . '_XDEL';
        //          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->save()) {
            $data = ['mess' => Yii::t('yii', 'Successfully canceled record.'), 'class' => 'success', 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Attempt to cancel unsuccessful. Error: {error}', [
                'error' => Json::encode($model->getErrors())
            ]), 'class' => 'warning'];
        }

        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash($data['class'], $data['mess']);
            return $this->redirect(['index']);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
    }

    /**
     * Duplicates an existing CadServidores model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id)
    {
        $model = $this->findModel($id);
        $idOriginal = $model->id;
        $id_n = CadServidores::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = CadServidores::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->matricula = self::getMatricula();
        $model->isNewRecord = true;
        //        return $idOriginal . ' - ' . $model->id;
        if (
            $model->save() &&
            $this->actionAC($idOriginal, $model->id, false) && $this->actionAC($idOriginal, $model->id, true)
        ) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                . "Dados do registro duplicado: "
                . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
        }
        return $this->redirect(['view', 'id' => $model->slug]);
    }

    /**
     * Your controller action to fetch the list
     */
    public function actionZ($id = null)
    {
        $query = new \yii\db\Query;
        $db = CadServidores::getDb()->dbname;
        $cadastros = CadServidores::tableName();

        $query->select([
            'id' => 'id',
            'nome' => 'Concat(nome, " (" , lpad(cast(matricula as unsigned), 8, "0"), ")")',
        ])
            ->from("$db.$cadastros")
            ->andFilterWhere([
                'or',
                ['like', "nome", "$id"],
                ['like', "lpad(matricula, 8, '0')", "$id"],
            ])
            ->andWhere(["$cadastros.dominio" => Yii::$app->user->identity->dominio])
            ->orderBy("$cadastros.nome")
            ->limit(5);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = [
                'value' => $d['nome'],
                'id' => $d['id'],
            ];
        }
        return Json::encode($out);
    }

    public function actionPhoto($id)
    {
        $model = new \common\models\UploadForm();
        $cad_first = $this->findModel($id, true);
        $model_cad = $this->findModel($id);
        $matricula = $model_cad->matricula;

        function base64_to_jpeg($base64_string, $output_file)
        {
            $return = true;
            $ifp = fopen($output_file, "wb");
            try {
                fwrite($ifp, base64_decode($base64_string));
                fclose($ifp);
            } catch (Exception $ex) {
                $return = false;
            }
            return $return;
        }

        if ($post = Yii::$app->request->post()) {
            $imagem = str_replace('data:image/png;base64,', '', $post['UploadForm']['base64']);
            $dominio = strtolower(trim(Yii::$app->user->identity->dominio));
            $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
            $source_file = strtolower(Yii::getAlias("@uploads_root/imagens/$cliente/$dominio/servidores/$matricula.png"));
            $dst_dir = strtolower(Yii::getAlias("@uploads_root/imagens/$cliente/$dominio/servidores/$matricula"));
            if (base64_to_jpeg($imagem, $source_file)) {
                ImgController::resize_crop_image(312, 312, $source_file, $dst_dir . "_crpd_234x312.png", 100);
                unlink($source_file);
                $model_cad->url_foto = Url::home(true) . Yii::getAlias("@uploads_url/imagens/$cliente/$dominio/servidores/") . $model_cad->matricula . "_crpd_234x312.png";
                if ($model_cad->save()) {
                    $cad_last = $this->findModel($id, true);
                    $model_cad->evento = SisEventsController::registrarUpdate($cad_first, $cad_last, Yii::$app->user->identity->id, $model_cad->tableName(), 'actionPhoto');
                    //                registra evento no log do sistema
                    $model_cad->save();
                    $data = Yii::t('yii', 'Successfully uploaded file');
                } else {
                    $data = Yii::t('yii', 'Erro: ') . Json::encode($model_cad);
                }
            } else {
                $data = Yii::t('yii', 'Erro no carregameento. Erro: {erro}', ['erro' => Json::encode($model->getErrors())]);
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $model_cad->slug]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('photo', [
                'model' => $model,
                'modal' => true,
            ]);
        } else {
            return $this->render('photo', [
                'model' => $model,
                'modal' => true,
            ]);
        }
    }

    public function actionUpload($id, $modal = false)
    {
        $model = new UploadForm();
        $cad = $this->findModel($id);
        $cad_first = $this->findModel($id, true);
        $dominio = strtolower(trim(Yii::$app->user->identity->dominio));
        $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $source_file = strtolower(Yii::getAlias("@uploads_root/imagens/$cliente/$dominio/servidores/"));
            if ($model->upload($source_file, $cad->matricula)) {
                foreach ($model->imageFiles as $file) {
                    $source_file .= $cad->matricula;
                    ImgController::resize_crop_image(312, 312, "$source_file.$file->extension", $source_file . "_crpd_234x312.$file->extension", 100);
                    $cad->url_foto = Url::home(true) . Yii::getAlias("@uploads_url/imagens/$cliente/$dominio/servidores/") . $cad->matricula . "_crpd_234x312.$file->extension";
                    $cad->save();
                    unlink($source_file . ".$file->extension");
                }
                $cad_last = $this->findModel($id, true);
                $cad->evento = SisEventsController::registrarUpdate($cad_first, $cad_last, Yii::$app->user->identity->id, $cad->tableName(), 'actionUpload');
                //                registra evento no log do sistema
                $cad->save();
                $data = Yii::t('yii', 'Successfully uploaded file');
            } else {
                $data = Yii::t('yii', 'Erro no carregameento. Erro: {erro}', ['erro' => Json::encode($model->getErrors())]);
            }
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $cad->slug]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('upload', [
                'model' => $model,
                'modal' => $modal,
                'imagem' => $cad->url_foto
            ]);
        } else {
            return $this->render('upload', [
                'model' => $model,
                'modal' => $modal,
                'imagem' => $cad->url_foto
            ]);
        }
    }

    /**
     * Verifica a existência do cpf informado
     * @param type $q
     * @return type
     */
    public function actionR($q, $i = null)
    {
        $cadastros = CadServidores::find()
            ->where(['=', 'cpf', str_replace('.', '', str_replace('-', '', $q))])
            ->andWhere(['dominio' => Yii::$app->user->identity->dominio,])
            ->andWhere(['!=', 'id', $i])
            ->all();
        /*
         * Caso não hajam cadastros com este cpf_cnpj ou em caso de edição
         * se o id do cadastro existente for igual ao id do cadastro em edição
         * então retorna null, permitindo a utilização do documento no cadastro
         */
        if ($cadastros == null) {
            return Json::encode(['id' => 0, 'nome' => null]);
        } else {
            foreach ($cadastros as $cadastro) {
                $result[] = [
                    'i' => $cadastro->id,
                    //                    'cds' => [
                    //                        'n' => $cadastro->cadas_nome,
                    //                        's' => $cadastro->slug,
                    'r' => Html::a($cadastro->nome . ' (' . $cadastro->matricula . ')', ['/cad-servidores/' . $cadastro->slug], ['title' => 'Clique para editar registro', 'data-toggle' => 'tooltip', 'data-placement' => 'top',]) .
                        Html::a(' <span class="fa fa-clone"></span>', ['/cad-servidores/dpl', 'id' => $cadastro->slug], ['title' => 'Clique para duplicar registro', 'data-toggle' => 'tooltip', 'data-placement' => 'top',]),
                    //                    'r' => Html::a()'<a href=' . Url::home() . 'cadastros/' . $cadastro->slug . '>' . $cadastro->nome . '(' . $cadastro->cpf . ')</a>',
                    //                    ]
                ];
            }
            return Json::encode(['id' => count($cadastros), 'result' => $result], JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Retorna o próximo número de matricula
     * @return type
     */
    public static function getMatricula()
    {
        return CadServidores::find()
            ->where(['dominio' => Yii::$app->user->identity->dominio])
            ->max('matricula') + 1;
    }

    /**
     * Imprime um recurso
     * @param type $id
     * @param type $id_f
     * @param type $format
     * @param type $fileName
     * @return type
     */
    public function actionPrint($id = null, $id_f = null, $format = 'pdf', $fileName = 'holerite')
    {
        if ($id_f == null) {
            $id_f = $id;
        }
        switch ($fileName) {
            case 'holerite':
                return $this->printHolerite($id, $id_f, $format);
                break;
            case 'fichaFinanceiraA':
                return $this->printFichaFinanceiraA($id, $format, $fileName);
                break;
            case 'fichaFinanceiraS':
                return $this->printFichaFinanceiraS($id, $format, $fileName);
                break;
        }
    }

    /**
     * Retorna o(s) holerite(s) solicitado(s) para impressão
     */
    public function printHolerite($id = null, $id_f = null, $format = 'pdf')
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        $db = Yii::$app->$db->dbname;
        $fileName = 'holerite';
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $dominio = Yii::$app->user->identity->dominio;
        $controls = array(
            'ano' => $ano,
            'mes' => $mes,
            'parcela' => $parcela,
            'id_cad_servidor' => $id,
            'id_cad_servidor_f' => $id_f,
            'dominio' => $dominio,
            'tableSchema' => $db,
            'URL_PRINTER' => Url::home(true),
            'URL_ROOT' => Url::home(true) . '../holerite-on-line?token=',
        );
        require_once(Yii::getAlias("@common/components/jasperserverintegration.php"));
        // Cria o objeto do JasperServerIntegration
        $jsi = new JasperServerIntegration(
            'http://ns1.mgcash.app.br:8080/jasperserver',     // URL do JasperServer
            "reports/MGFolha/cad_servidores/$fileName",     // Caminho do relatório no JasperServer sem a primeira barra
            $format,                                        // Tipo da exportação do relatório
            'caixa',                                  // Usuário com acesso ao relatório
            'C@1xa',                                   // Senha do usuário
            $controls                                       // Array com os parâmetros (opcional)
        );

        // Executa o relatório
        try {
            $data = $jsi->execute();
            SisEventsController::registrarEvento("Holerite impresso com sucesso! Registros: $id ao $id_f", 'printHolerite', Yii::$app->user->identity->id, 'cad_servidores', $id);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (Exception $e) {
            $eMsg = $e->getMessage();
            $eCode = $e->getCode();
            print("Erro - $eCode: $eMsg.");
        }
    }

    /**
     * Retorna a ficha financeira solicitada para impressão
     */
    public function printFichaFinanceiraS($id = null, $format = 'pdf', $fileName = 'fichaFinanceiraS')
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        $db = Yii::$app->$db->dbname;
        $ano = Yii::$app->user->identity->per_ano;
        $mes = Yii::$app->user->identity->per_mes;
        $parcela = Yii::$app->user->identity->per_parcela;
        $dominio = Yii::$app->user->identity->dominio;
        $controls = array(
            'ano' => $ano,
            'mes' => $mes,
            'parcela' => $parcela,
            'id_cad_servidor' => $id,
            'dominio' => $dominio, 
            'id_usuario' => Yii::$app->user->identity->id,
            'tableSchema' => $db,
        );
        require_once(Yii::getAlias("@common/components/jasperserverintegration.php"));
        // Cria o objeto do JasperServerIntegration
        $jsi = new JasperServerIntegration(
            'http://ns1.mgcash.app.br:8080/jasperserver',     // URL do JasperServer
            "reports/MGFolha/cad_servidores/$fileName",     // Caminho do relatório no JasperServer sem a primeira barra
            $format,                                        // Tipo da exportação do relatório
            'caixa',                                  // Usuário com acesso ao relatório
            'C@1xa',                                   // Senha do usuário
            $controls                                       // Array com os parâmetros (opcional)
        );

        // Executa o relatório
        try {
            $data = $jsi->execute();
            SisEventsController::registrarEvento("Ficha financeira sintética impressa com sucesso!", 'printFichaFinanceiraS', Yii::$app->user->identity->id, 'cad_servidores', $id);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (Exception $e) {
            $eMsg = $e->getMessage();
            $eCode = $e->getCode();
            print("Erro - $eCode: $eMsg.");
        }
    }

    /**
     * Retorna a ficha financeira solicitada para impressão
     */
    public function printFichaFinanceiraA($id, $format = 'pdf', $fileName = 'fichaFinanceiraA')
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        $db = Yii::$app->$db->dbname;
        $ano = Yii::$app->user->identity->per_ano;
        $dominio = Yii::$app->user->identity->dominio;
        $controls = array(
            'ano' => "ff.ano='$ano'",
            'mes' => "ff.mes%20between%20'01'%20and%20'13'",
            'parcela' => "ff.parcela%20between'0'and'99'",
            'id_cad_servidores' => "cs.id=$id",
            'dominio' => $dominio,
            'id_usuario' => Yii::$app->user->identity->id,
            'titulo' => "Ficha%20Financeira%20Analitica",
            'descricao' => "Ano%20$ano",
            'tableSchema' => $db,
        );
        // return Json::encode($controls);
        require_once(Yii::getAlias("@common/components/jasperserverintegration.php"));
        // Cria o objeto do JasperServerIntegration
        $jsi = new JasperServerIntegration(
            'http://ns1.mgcash.app.br:8080/jasperserver',     // URL do JasperServer
            "reports/MGFolha/cad_servidores/$fileName",     // Caminho do relatório no JasperServer sem a primeira barra
            $format,                                        // Tipo da exportação do relatório
            'caixa',                                  // Usuário com acesso ao relatório
            'C@1xa',                                   // Senha do usuário
            $controls                                       // Array com os parâmetros (opcional)
        );

        // Executa o relatório
        try {
            $data = $jsi->execute();
            SisEventsController::registrarEvento("Ficha financeira analítica impressa com sucesso!", 'printFichaFinanceiraS', Yii::$app->user->identity->id, 'cad_servidores', $id);
            header('Content-Type: application/pdf');
            echo $data;
        } catch (Exception $e) {
            $eMsg = $e->getMessage();
            $eCode = $e->getCode();
            print("Erro - $eCode: $eMsg.");
        }
    }

    /**
     * Retorna algumas tags de dados aprimorados para a impressao a executar a 
     * partir do nome do arquivo .jasper a imprimir
     * @return string
     */
    public static function getImpressoes($f)
    {
        switch ($f) {
            case 'fichaFinanceiraA':
                $return = ['filename' => $f, 'titulo' => 'Ficha Financeira Analítica'];
                break;
            case 'fichaFinanceiraS':
                $return = ['filename' => $f, 'titulo' => 'Ficha Financeira Sintética'];
                break;
        }
        return $return;
    }

    /**
     * Finds the CadServidores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CadServidores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false, $id_f = null)
    {
        $model = CadServidores::find();
        // Se a consulta for por uma faixa de ID
        if ($id_f != null && is_numeric($id)) {
            $model = $model->where([
                'dominio' => Yii::$app->user->identity->dominio,
            ])
                ->andWhere(['between', 'id', $id, $id_f]);
            if ($model !== null) {
                if ($array) {
                    return $model->asArray()->all();
                } else {
                    return $model->all();
                }
            }
        } else {
            $model = $model->where([
                is_numeric($id) ? 'id' : 'slug' => $id,
                'dominio' => Yii::$app->user->identity->dominio,
            ]);
            if ($model !== null) {
                if ($array) {
                    return $model->asArray()->one();
                } else {
                    return $model->one();
                }
            }
        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }
}
