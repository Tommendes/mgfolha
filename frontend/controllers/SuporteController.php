<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Suporte;
use frontend\models\SuporteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\behaviors\SluggableBehavior;

/**
 * SuporteController implements the CRUD actions for Suporte model.
 */
class SuporteController extends Controller {

    const CLASS_VERB_NAME = 'Suporte';

    public $administrador = 0;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->administrador = Yii::$app->user->identity->administrador;
        }

        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'slug',
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['suporte', 'artigos', 'videos', 'index'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true; //!Yii::$app->user->isGuest;
                        }
                    ],
                    ['actions' => ['view', 'adm', 'create', 'duplicate', 'u', 'delete'],
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
     * Visualiza o artigo de ajuda
     * @param string $id
     * @return mixed
     */
    public function actionArtigos($id = null) {
        if (!$id == null) {
            $model = $this->findModel($id);
            if ($model !== null) {
                return $this->render('artigos', [
                            'model' => $model,
                ]);
            } else {
                throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist'));
            }
        } else {
            return $this->actionIndex();
        }
    }

    /**
     * Visualiza o artigo de ajuda
     * @param string $slug
     * @return mixed
     */
    public function actionVideos($id = null) {
        return $this->render('videos', [
                    'id' => $id,
        ]);
    }

    public function actionSuporte() {
        return $this->render('support');
    }

    /**
     * Lists all Suporte models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SuporteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //gera evento para ser gravado no log do sistema 
        SisEventsController::registrarEvento("Visualização de registros bem sucedida.", 'actionIndex', Yii::$app->user->identity->id, $searchModel->tableName());

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Suporte models.
     * @return mixed
     */
    public function actionAdm() {
        $searchModel = new SuporteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //gera evento para ser gravado no log do sistema 
        SisEventsController::registrarEvento("Visualização de registros bem sucedida.", 'actionAdm', Yii::$app->user->identity->id, $searchModel->tableName());

        return $this->render('adm', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cadastros model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        SisEventsController::registrarEvento("Visualização de registro bem sucedida. Cod. registro: " . $model->id, 'actionView', Yii::$app->user->identity->id, $this->findModel($id)->tableName());
        $post = Yii::$app->request->post();
        if ($this->administrador >= 1) {
//          gera evento para ser gravado no log do sistema 
            if (Yii::$app->request->post()) {
//          gera evento antes da edição para ser gravado no log do sistema 
                $evento = 'Registro editado com sucesso em: ' . $model->tableName() . '. Dados antes da edição:' . SisEventsController::evt($model);
            }
            if ($model->load($post) && $model->save()) {
                $evento .= 'Dados após edição: ' . SisEventsController::evt($model);
//          registra evento no log do sistema
                $model->evento = SisEventsController::registrarEvento($evento, 'actionUpdate', Yii::$app->user->identity->id, $model->tableName());
                $model->save();
//            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('yii', 'Access denied to this operation: {operation}', ['operation' => 'edição']));
        }
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new Suporte model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Suporte();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//          gera evento para ser gravado no log do sistema 
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionCreate', Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
            return $this->redirect([
                        'view', 'id' => $model->slug,
            ]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Suporte model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionU($id) {
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
            $model->evento = SisEventsController::registrarEvento($evento, 'actionUpdate', Yii::$app->user->identity->id, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(['view', 'id' => $model->id, 'mv' => 'view']);
        } else {
            return $this->redirect(['view', 'id' => $model->id, 'mv' => 'edit']);
//            return $this->render('update', [
//                        'model' => $model,
//                        
//            ]);
        }
    }

    /**
     * Deletes an existing Suporte model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
//        $model->delete();
        $model->status = Suporte::STATUS_CANCELADO; //Definir regras de status do registro
        try {
            $model->save();
            SisEventsController::registrarEvento("Registro desativado com sucesso! Dados do registro desativado: " . SisEventsController::evt($model), 'actionDelete', Yii::$app->user->identity->id, $model->tableName());

            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully deactived record.'));

            return $this->goBack();
//            return $this->redirect([
//                        'index',
//            ]);
        } catch (ErrorException $e) {
            SiteController::setAlert(3, 'danger', "Erro ao tentar salvar o registro. Erro: " . $e);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Duplicates an existing defined model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDuplicate($id) {
        $model = $this->findModel($id);
        $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                        . "Dados do registro duplicado: "
                        . SisEventsController::evt($model), 'actionDuplicate', isset(Yii::$app->user->identity->username) ?
                        Yii::$app->user->identity->username : 'Visitante', $model->tableName());
        $model->save();
        $id_n = Suporte::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = Suporte::STATUS_ATIVO; //Definir regras de status do registro
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionCreate', Yii::$app->user->identity->id, $model->tableName());
        $model->isNewRecord = true;
        try {
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $id_n, 'mv' => 'edit']);
        } catch (ErrorException $e) {
            SiteController::setAlert(3, 'danger', "Erro ao tentar duplicar o registro. Erro: " . $e);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Finds the Suporte model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Suporte the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Suporte::findOne([
                    is_numeric($id) ? 'id' : 'slug' => $id,
                ])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist'));
        }
    }

}
