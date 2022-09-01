<?php

namespace pagamentos\controllers;

use Yii;
use pagamentos\models\FinFaixas;
use pagamentos\models\FinFaixasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;

/**
 * FinFaixasController implements the CRUD actions for FinFaixas model.
 */
class FinFaixasController extends Controller {

    public $gestor = 0;
    public $finfaixas = 0;

    const CLASS_VERB_NAME = 'Tabela';
    const CLASS_VERB_NAME_PL = 'Tabelas';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->finfaixas = Yii::$app->user->identity->financeiro;
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
                        'actions' => ['index', 'view', 'z'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->finfaixas >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finfaixas >= 2 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finfaixas >= 3 || $this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->finfaixas >= 4 || $this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all FinFaixas models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new FinFaixasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Localiza e exibe a faixa atual.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionZ($tipo = null, $modal = false, $mv = null) {
//        $model = $this->findModelLast($tipo);
        $model = FinFaixas::find()->where([
                    'dominio' => Yii::$app->user->identity->dominio,
                    'tipo' => $tipo,
                ])
                ->orderBy(['STR_TO_DATE(`data`, "%d/%m/%Y")' => SORT_DESC])
                ->limit(1)
                ->all();
        return $this->actionView($model, $modal, $mv);
    }

    /**
     * Displays a single FinFaixas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = null, $modal = false, $mv = null) {
        $model = $this->findModel($id);
        $model_first = $this->findModel($id, true);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
//          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, Yii::$app->user->identity->id, $model->tableName());
            $model->save();
//            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            //return $this->redirect(Url::home(true) . str_replace('_', '-', $model->tableName()) . '/' . $model->slug);
            Yii::$app->session->setFlash('kv-detail-success', Yii::t('yii', 'Successfully updated record.'));
            if ($modal) {
                return $this->redirect(['index']);
            } else {
                return $this->redirect(['view', 'id' => $model->slug]);
            }
        } else {
            if (!$model->load($post)) {
                $evento = 'Registro ' . $model->id . ' visualizado com sucesso em: ' . $model->tableName() . '. Módulo acessado: ' . ($mv == \kartik\detail\DetailView::MODE_EDIT ? 'Editar' : 'Ver');
                SisEventsController::registrarEvento($evento, 'actionView', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            }
            if ($model->load($post) && !$model->save()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Por favor verifique os erros abaixo antes de prosseguir'));
            }
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

    /**
     * Creates a new FinFaixas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new FinFaixas();

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
     * Updates an existing FinFaixas model.
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
     * Deletes an existing FinFaixas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete();

        $model->status = FinFaixas::STATUS_CANCELADO;
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
     * Duplicates an existing FinFaixas model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id

     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = FinFaixas::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = FinFaixas::STATUS_ATIVO;
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
     * Finds the FinFaixas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinFaixas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = FinFaixas::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = FinFaixas::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }
//        if (($model = FinFaixas::findOne([
//                    is_numeric($id) ? 'id' : 'slug' => $id,
//                    'dominio' => Yii::$app->user->identity->dominio,
//                ])) !== null) {
//            return $model;
//        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

    /**
     * Localiza a faixa corrente baseada em fin_faixa.data DESC
     * @param type $tipo
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findModelLast($tipo) {
        if (($model = FinFaixas::find()->where([
                    'dominio' => Yii::$app->user->identity->dominio,
                    'tipo' => $tipo,
                ])
                ->orderBy(['STR_TO_DATE(`data`, "%d/%m/%Y")' => SORT_DESC])
                ->one()) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

}
