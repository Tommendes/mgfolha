<?php

namespace pagamentos\controllers;

use Yii;
use common\models\UserOptions;
use common\models\UserOptionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Json;
use common\models\User;
use common\models\Params;
use yii\helpers\Url;

/**
 * UserOptionsController implements the CRUD actions for UserOptions model.
 */
class UserOptionsController extends Controller {

    public $gestor = 0;
    public $UserOptions = 0;

    const CLASS_VERB_NAME = 'Opções do usuário';
    const CLASS_VERB_NAME_PL = 'Opções dos usuários';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->UserOptions = Yii::$app->user->identity->usuarios;
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
                        'actions' => ['set-geo'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->UserOptions >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->UserOptions >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all UserOptions models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UserOptionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserOptions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        SisEventsController::registrarEvento("Visualização de registro bem sucedida. Cod. registro: " . $model->id, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $this->findModel($id)->tableName());
        $post = Yii::$app->request->post();
        $evento = 'Registro visualizado com sucesso em: ' . $model->tableName();
        if (Yii::$app->request->post()) {
//          gera evento antes da edição para ser gravado no log do sistema 
            $evento = 'Registro editado com sucesso em: ' . $model->tableName() . '. Dados antes da edição:' . SisEventsController::evt($model);
        }
        if ($model->load($post) && $model->save()) {
            $evento .= 'Dados após edição: ' . SisEventsController::evt($model);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarEvento($evento, 'actionU', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
//            if ($model->load($post) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . 'user/' . $model->slug);
        }
        SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new UserOptions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new UserOptions();

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
     * Updates an existing UserOptions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionU($id, $modal = false) {
        $model = $this->findModel($id);
//      gera evento para ser gravado no log do sistema 
        if (Yii::$app->request->post()) {
//          gera evento antes da edição para ser gravado no log do sistema 
            $evento = 'Registro editado com sucesso em: ' . $model->tableName() . '. Dados antes da edição:' . SisEventsController::evt($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento após a edição para ser gravado no log do sistema 
            $evento .= 'Dados após edição: ' . SisEventsController::evt($model);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarEvento($evento, 'actionU', Yii::$app->user->identity->id, $model->tableName(), $model->id);
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
     * Deletes an existing UserOptions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = UserOptions::STATUS_CANCELADO;
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
     * Duplicates an existing UserOptions model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = UserOptions::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = UserOptions::STATUS_ATIVO;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                            . "Dados do registro duplicado: "
                            . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->id, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->slug]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Salva a geolocalização do navegador do usuário
     * @param type $id
     * @param type $geo_lt
     * @param type $geo_ln
     */
    public function actionSetGeo($id = null, $geo_lt = null, $geo_ln = null) {
        $model = $this->findModel($id);
        if (!$model == null) {
            $model->geo_lt = $geo_lt;
            $model->geo_ln = $geo_ln;
            $model->save();
            if ($model->save()) {
                return \yii\helpers\Json::encode(['result' => true, 'geo_lt' => $geo_lt, 'geo_ln' => $geo_ln]);
            } else {
                return 'Relatar para o dev master: ' . $model->getErrors();
            }
        } else {
            return 'Usuário não existe: ' . $id;
        }
    }

    public static function createDefault() {
        $def_per = ['i' => date("Y-m") . '-1', 'f' => date("Y-m-t"),];
        $def_per = json_encode($def_per);
        $model = new UserOptions;
        $model->id_user = !Yii::$app->user->isGuest ? Yii::$app->user->identity->id : 0;
        $model->geo_lt = NULL;
        $model->geo_ln = NULL;
        $model->evento = SisEventsController::registrarEvento("UsuarioOpcoes incluido com sucesso", 'createDefault', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
        $model->created_at = strtotime(date('Y-m-d'));
        $model->updated_at = strtotime(date('Y-m-d'));
        $model->save();
        return $model;
    }

    /**
     * Finds the UserOptions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserOptions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function findModel($id) {
        if (($model = UserOptions::find()
                ->where([
                    is_numeric($id) ? 'user.id' : 'user.slug' => $id,
                ])
                ->join('JOIN', User::tableName(), User::tableName() . '.id = ' . UserOptions::tableName() . '.id_user')
                ->one()) == null) {
            return UserOptionsController::createDefault();
        }
        return $model;
    }

}
