<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Msgs;
use frontend\models\MsgsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\models\DeskUsers;
use yii\web\Response;
use yii\helpers\Json;

/**
 * MsgsController implements the CRUD actions for Msgs model.
 */
class MsgsController extends Controller {

    public $gestor = 0;
    public $administrador = 0;

    const CLASS_VERB_NAME = 'Mensagem ao cliente';
    const CLASS_VERB_NAME_PL = 'Mensagens ao cliente';

    /**
     * @inheritdoc
     */
    public function behaviors() {
        //Yii::$app->session->destroy('kv-detail-success');
        //Yii::$app->session->destroy('kv-detail-warning');
        if (!Yii::$app->user->isGuest) {
            $this->gestor = Yii::$app->user->identity->gestor;
            $this->administrador = Yii::$app->user->identity->administrador;
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'dlt' => ['POST'],
                    'replicate' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['msgs-desk', 'z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index', 'view', 'c', 'dpl', 'replicate'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return $this->administrador >= 1;
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return $this->administrador >= 1;
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return $this->administrador >= 1;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Msgs models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new MsgsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Msgs model.  
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionMsgsDesk($id = null) {
        $model = Msgs::findOne(['slug' => $id]);
        return$this->renderPartial('tasks', ['model' => $model]);
    }

    /**
     * Edita o status para LIDA
     * @param type $id
     * @return type
     */
    public function actionZ($id = null) {
        $retorno = false;
        if (($model_first = Msgs::find()
                ->where([
                    is_numeric($id) ? 'id' : 's lug' => $id
                ])
                ->one()) != null) {
            $model_first->status = Msgs::STATUS_LIDA;
            $retorno = $model_first->save();
            $model_last = $model_first;
            $model_last->evento = SisEventsController::registrarUpdate($model_first, $model_last, isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model_first->tableName());
            $model_last->save();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ([
            'result' => $retorno,
        ]);
    }

    /**
     * Displays a single Msgs model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null) {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'msgs/' . $model->slug);
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
     * Creates a new Msgs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new Msgs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
     * Updates an existing Msgs model.
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
     * Deletes an existing Msgs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete(); 

        $model->status = Msgs::STATUS_CANCELADO;
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
            return $this->redirect(['index']);
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $data;
        }
    }

    /**
     * Duplicates an existing Msgs model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = Msgs::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = Msgs::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->username, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->slug, 'mv' => \kartik\detail\DetailView::MODE_EDIT]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Duplicates an existing defined model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReplicate($id) {
        $model = $this->findModel($id);
        $id_desk_users = MsgsController::getDeskUsers($model->id_desk_user);
        foreach ($id_desk_users as $id_desk_user) {
            $id_n = Msgs::find()->max('id') + 1;
            $model->id = $id_n;
            $model->slug = sha1(Yii::$app->security->generateRandomString());
            $model->status = Msgs::STATUS_ATIVO;
            $model->id_desk_user = $id_desk_user->id;
            $model->isNewRecord = true;
            if ($model->save()) {
                SisEventsController::registrarEvento("Registro replicado com sucesso! "
                        . "Dados do registro replicado: "
                        . SisEventsController::evt($model), 'actionReplicate', isset(Yii::$app->user->identity->username) ?
                                Yii::$app->user->identity->username : 'Visitante', $model->tableName());
                Yii::$app->session->setFlash('kv-detail-success', Yii::t('yii', 'Successfully replicated record'));
            } else {
                Yii::$app->session->setFlash('kv-detail-warning', Yii::t('yii', 'Attempt to replicate unsuccessful. Error: ') . $model->getErrors());
                return $this->redirect(['view', 'id' => $id]);
            }
        }
        return $this->actionIndex();
    }

    /**
     * Finds the Msgs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Msgs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Msgs::findOne([
                    is_numeric($id) ? 'id' : 'slug' => $id
                ])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Retorna todos os usuários DeskTop do MGFolha V3
     * @return type
     */
    public static function getDeskUsers($id_desk_user = null) {
        return DeskUsers::find()
                        ->select([
                            'id',
                            'cli_nome' => 'concat(dll_nome, " -> ", cli_nome_comput, " -> ", cli_nome_user)'
                        ])
                        ->where($id_desk_user != null ? ['!=', 'id', $id_desk_user] : '1=1')
                        ->groupBy('dll_cod, dll_nome, dll_cnpj, cli_nome_user, cli_nome_comput')
                        ->orderBy('dll_nome, cli_nome_comput, cli_nome_user')
                        ->all();
    }

}
