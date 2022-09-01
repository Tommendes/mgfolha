<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\CadScertidao;
use pagamentos\models\CadScertidaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use pagamentos\models\CadServidores;

/**
 * CadScertidaoController implements the CRUD actions for CadScertidao model.
 */
class CadScertidaoController extends Controller {

    public $gestor = 0;
    public $cadscertidao = 0;

    const CLASS_VERB_NAME = 'Certidão';
    const CLASS_VERB_NAME_PL = 'Certidões';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->cadscertidao = Yii::$app->user->identity->cadastros;
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
                            return !Yii::$app->user->isGuest && $this->cadscertidao >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadscertidao >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadscertidao >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->cadscertidao >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CadScertidao models.
     * @return mixed
     */
    public function actionIndex($id_cad_servidores = null, $modal = false) {
        if (!is_null($id_cad_servidores)):
            $searchModel = new CadScertidaoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id_cad_servidores);

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
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para acessar o registro de uma certidão, localize primeiro o cadastro do servidor a quem ela pertence'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Displays a single CadScertidao model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true, false);
        $model = $this->findModel($id, false, false);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true, false);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
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
    }

    /**
     * Creates a new CadScertidao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($id_cad_servidores = null, $modal = false, $mv = null) {
        if (!is_null($id_cad_servidores) &&
                (($cadServidor = CadServidores::findOne($id_cad_servidores)) != null)):
            $model = new CadScertidao();
            $model->id_cad_servidores = $cadServidor->id;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
                $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', Yii::$app->user->identity->id, $model->tableName(), $model->id);
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
                return $this->redirect(['view', 'id' => $model->slug, 'sfm' => false]);
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_form', [
                            'model' => $model,
                            'modal' => $modal,
                            'id_cad_servidores' => $id_cad_servidores,
                ]);
            } else {
                return $this->render('create', [
                            'model' => $model,
                            'modal' => $modal,
                            'id_cad_servidores' => $id_cad_servidores,
                ]);
            }
        else:
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Para registrar um dependente, localize primeiro o cadastro do servidor responsável'));
            return $this->redirect([Url::to('/cad-servidores')]);
        endif;
    }

    /**
     * Updates an existing CadScertidao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        return $this->actionView($id, $modal);
//        $model_first = $this->findModel($id, true, true);
//        $model = $this->findModel($id, false, true);
//        $post = Yii::$app->request->post();
//        if ($model->load($post) && $model->save()) {
//            $model_last = $this->findModel($id, true, true);
////          registra evento no log do sistema
//            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
//            $model->save();
//            $data = Yii::t('yii', 'Successfully updated record.');
//            if (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return $data;
//            } else {
//                Yii::$app->session->setFlash('success', $data);
//                return $this->redirect(Url::home(true) . 'cad-servidores/' . $model->getCadServidor()->slug . '?mv=' . $mv);
//            }
//        }
//        if (Yii::$app->request->isAjax) {
//            return $this->renderAjax('_form', [
//                        'model' => $model,
//                        'modal' => $modal,
//            ]);
//        } else {
//            return $this->render('update', [
//                        'model' => $model,
//                        'modal' => $modal,
//            ]);
//        }
    }

    /**
     * Deletes an existing CadScertidao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//          registra evento no log do sistema
        SisEventsController::registrarEvento("Registro cancelado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDlt', Yii::$app->user->identity->id, $model->tableName(), $model->id);
        if ($model->delete()) {
            $data = ['mess' => Yii::t('yii', 'Successfully deleted record'), 'class' => 'success', 'stat' => true];
        } else {
            $data = ['mess' => Yii::t('yii', 'Attempt to delete unsuccessful. Error: {error}', [
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
     * Duplicates an existing CadScertidao model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = CadScertidao::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = CadScertidao::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
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
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne($id_cad_servidores);
    }

    /**
     * 
     * Finds the CadScertidao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param type $id
     * @param type $array
     * @param type $sfm acronym to search for matricula
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $array = false) {
        $loc = CadScertidao::tableName();
        $model = CadScertidao::find()
                ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . $loc . '.id_cad_servidores')
                ->where([
            is_numeric($id) ? $loc . '.id' : $loc . '.slug' => $id,
        ]);
        if ($array) {
            if (($model = $model->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = $model->one()) !== null) {
                return $model;
            }
        }
//        if (($model = CadScertidao::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
