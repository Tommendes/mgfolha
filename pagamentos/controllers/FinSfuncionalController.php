<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\FinSfuncional;
use pagamentos\models\FinSfuncionalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use pagamentos\models\CadServidores;

/**
 * FinSfuncionalController implements the CRUD actions for FinSfuncional model.
 */
class FinSfuncionalController extends Controller {

    public $gestor = 0;
    public $finsfuncional = 0;

    const CLASS_VERB_NAME = 'Financeiro do servidor';
    const CLASS_VERB_NAME_PL = 'Financeiro dos servidores';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->finsfuncional = Yii::$app->user->identity->financeiro;
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
                        'actions' => ['index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1;
                        }
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->finsfuncional >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 's-c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finsfuncional >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finsfuncional >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finsfuncional >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all FinSfuncional models.
     * @return mixed
     */
    public function actionIndex($id_cad_servidores = null, $modal = false) {
        if (!is_null($id_cad_servidores) &&
                (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null)):
            $searchModel = new FinSfuncionalSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $cadServidor->id);

            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $id_cad_servidores,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'id_cad_servidores' => $id_cad_servidores,
                            'modal' => $modal,
                ]);
            }
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para acessar um registro de funcional, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Displays a single FinSfuncional model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if (!$model == null) {
            if ($model->load($post) && $model->save()) {
                $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
                $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
                $model->save();
                $ret = FolhaController::setAlterFolhaServidor(FolhaController::OPERACOES_DA_GERACAO_INI_INDIVIDUAL, $model->id_cad_servidores);
                Yii::$app->session->setFlash('kv-detail-success', Yii::t('yii', 'Successfully updated record.'));
                Yii::$app->session->setFlash('kv-detail-info', Yii::t('yii', $ret));
                if ($modal) {
                    return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
                } else {
                    $matricula = $model->getCadServidor()->matricula;
                    return $this->redirect(["fin-sfuncional/$matricula?modal=$modal&mv=$mv"]);
                }
            } else {
                if (!$model->load($post)) {
                    $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                    SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
                }
                if ($model->load($post) && !$model->save()) {
                    Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
                }
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('view', [
                                'model' => $model,
                                'modal' => $modal,
                    ]);
                } else {
                    return $this->render('view', [
                                'model' => $model,
                                'modal' => $modal,
                    ]);
                }
            }
        } else {
            return $this->redirect(['/cad-servidores/']);
        }
    }

    /**
     * Creates a new FinSfuncional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($id_cad_servidores = null, $modal = false, $mv = null) {
        if (!is_null($id_cad_servidores) &&
                (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null)):
            $model = new FinSfuncional();
            $model->id_cad_servidores = $cadServidor->id;
            $model->ano = Yii::$app->user->identity->per_ano;
            $model->mes = Yii::$app->user->identity->per_mes;
            $model->parcela = Yii::$app->user->identity->per_parcela;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
                $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
//                return $this->redirect(['view', 'id' => $model->slug]);
                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
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
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para registrar um dependente, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Updates an existing FinSfuncional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
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
//                return $this->redirect(['view', 'id' => $model->slug]);
                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
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
     * Deletes an existing FinSfuncional model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = FinSfuncional::STATUS_CANCELADO;
        $model->dominio = $model->dominio . '_XDEL';
//          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->save(false)) {
            $data = ['mess' => Yii::t('yii', 'Successfully canceled record.'), 'class' => 'success', 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Attempt to cancel unsuccessful. Error: {error}', [
                    'error' => Json::encode($model->getErrors())
                ]), 'class' => 'warning'];
        }

        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash($data['class'], $data['mess']);
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
    }

    /**
     * Duplicates an existing FinSfuncional model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = FinSfuncional::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = FinSfuncional::STATUS_ATIVO;
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->username, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Cria um novo model FinSfuncional inserindo os dados tendo como referência o mês de informação
     * @param type $id Informar o id_cad_servidor original que serviu para a duplicação do registro
     * @param type $id_novo Informar o id_cad_servidor novo
     * @return string
     */
    public function actionSC($id, $id_novo, $decimo = false) {
        // Recebe o parâmetro
        $finParametro = FinParametrosController::getParametroAtual();
        // Recupera o último registro FinSFuncional do servidor utilizando o 
        // periodo de informação da folha atual
        $fin_sfuncional = FinSFuncional::find()
                        ->join('join', 'cad_servidores', 'cad_servidores.id = fin_sfuncional.id_cad_servidores')
                        ->where([
                            is_numeric($id) ? 'cad_servidores.id' : 'cad_servidores.slug' => $id,
                            'fin_sfuncional.dominio' => Yii::$app->user->identity->dominio,
                            'fin_sfuncional.ano' => $finParametro->ano_informacao,
                            'fin_sfuncional.mes' => $finParametro->mes_informacao,
                            'fin_sfuncional.parcela' => $finParametro->parcela_informacao,
                        ])->one();
        // Cria registro novo em FinSFuncional baseado no registro localizado
        $fin_sfuncional->isNewRecord = true;
        $id_n = FinSfuncional::find()->max('id') + 1;
        $fin_sfuncional->id = $id_n;
        $fin_sfuncional->status = FinSfuncional::STATUS_ATIVO;
        $fin_sfuncional->id_cad_servidores = $id_novo;
        $fin_sfuncional->slug = strtolower(sha1(Yii::$app->security->generateRandomString()));
        $fin_sfuncional->created_at = $fin_sfuncional->updated_at = time();
        $fin_sfuncional->ano = Yii::$app->user->identity->per_ano;
        $fin_sfuncional->mes = !$decimo ? Yii::$app->user->identity->per_mes : '13';
        $fin_sfuncional->parcela = Yii::$app->user->identity->per_parcela;
        if ($fin_sfuncional->save(false)) {
            $fin_sfuncional->evento = SisEventsController::registrarEvento("Registro financeiro criado com sucesso! "
                            . "Dados do registro criado: "
                            . SisEventsController::evt($fin_sfuncional), 'getSC', Yii::$app->user->identity->id, $fin_sfuncional->tableName(), $fin_sfuncional->id);
            $fin_sfuncional->save(false);
            $data = ['mess' => Yii::t('yii', 'Registro financeiro criado com sucesso!'), 'class' => Yii::$app->security->generateRandomString(5), 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Tentativa de criação do registro financeiro mau sucedida. Erro: {error}', [
                    'error' => Json::encode($fin_sfuncional->getErrors())
                ]), 'class' => 'warning'];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * Finds the FinSfuncional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinSfuncional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false, $sfm = true) {
        $loc = FinSfuncional::tableName();
        $model = FinSfuncional::find()
                ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
                ->where([
                    ((is_numeric($id) || $sfm) ? $cad . '.matricula' : $loc . '.slug') => $id,
                    $loc . '.dominio' => Yii::$app->user->identity->dominio,
                    $loc . '.parcela' => Yii::$app->user->identity->per_parcela,
                ])
                ->andWhere([
            $loc . '.ano' => Yii::$app->user->identity->per_ano,
            $loc . '.mes' => Yii::$app->user->identity->per_mes,
        ]);
        if ($array) {
            if (($model = $model->asArray()->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro financeiro para o servidor'));
                return null;
            }
        } else {
            if (($model = $model->one()) !== null) {
                return $model;
            } else {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'No período informado não há registro financeiro para o servidor'));
                return null;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Retorna os valores em BD.get_tempo_carteira
     * @param type $id
     * @param type $v_ano
     * @param type $v_mes
     * @param type $v_parcela
     * @return type
     */
    public static function getCarteira($id, $v_ano = null, $v_mes = null, $v_parcela = null) {
        $v_ano = $v_ano ?? Yii::$app->user->identity->per_ano;
        $v_mes = $v_mes ?? Yii::$app->user->identity->per_mes;
        $v_parcela = $v_parcela ?? Yii::$app->user->identity->per_parcela;
        $referencias = FinReferenciasController::getVctoBasico($id, $v_ano, $v_mes, $v_parcela);
        return $referencias;
    }

    /**
     * Retorna um array contendo os tipos de ênios
     */
    public static function getListaEnios() {
        $model = [
            1 => FinSfuncional::ENIO_1,
            3 => FinSfuncional::ENIO_3,
            5 => FinSfuncional::ENIO_5,
            10 => FinSfuncional::ENIO_10,
        ];
        return $model;
    }

    /**
     * Retorna quantos enios o servidor tem
     * @param type $enio
     * @param type $id_cad_servidores
     * @return string
     */
    public static function getEnios($enio, $id_cad_servidores) {
        $carteira = self::getCarteira($id_cad_servidores);
        switch ($enio) {
            case 1: $return = $carteira['v_anos'];
                break;
            case 3: $return = intdiv($carteira['v_anos'], 3);
                break;
            case 5: $return = intdiv($carteira['v_anos'], 5);
                break;
            case 10: $return = intdiv($carteira['v_anos'], 10);
                break;
            default : $return = 'Sem ênios';
        }
        return $return;
    }

    /**
     * Retorna o label do ênio
     */
    public static function getEnioLabel($tipo) {
        switch ($tipo) {
            case 1: $return = FinSfuncional::ENIO_1;
                break;
            case 3: $return = FinSfuncional::ENIO_3;
                break;
            case 5: $return = FinSfuncional::ENIO_5;
                break;
            case 10: $return = FinSfuncional::ENIO_10;
                break;
            default : $return = 'Sem ênios';
        }
        return $return;
    }

}
