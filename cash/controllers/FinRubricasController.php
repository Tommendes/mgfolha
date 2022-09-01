<?php

namespace cash\controllers;

use Yii;
use pagamentos\models\FinRubricas;
use pagamentos\models\FinRubricasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;

/**
 * FinRubricasController implements the CRUD actions for FinRubricas model.
 */
class FinRubricasController extends Controller {

    public $gestor = 0;
    public $finrubricas = 0;

    const CLASS_VERB_NAME = 'Rubrica(evento)';
    const CLASS_VERB_NAME_PL = 'Rubricas(eventos)';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->finrubricas = Yii::$app->user->identity->financeiro;
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
                            return !Yii::$app->user->isGuest && $this->finrubricas >= 1;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all FinRubricas models.
     * @return mixed
     */
    public function actionIndex($matricula, $modal = false) {
        $searchModel = new FinRubricasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $matricula);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('@pagamentos/views/fin-rubricas/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'modal' => $modal,
                        'matricula' => $matricula,
            ]);
        } else {
            return $this->render('@pagamentos/views/fin-rubricas/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'modal' => $modal,
                        'matricula' => $matricula,
            ]);
        }
    }

    /**
     * Displays a single FinRubricas model.
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
                return $this->renderAjax('@pagamentos/views/fin-rubricas/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            } else {
                return $this->render('@pagamentos/views/fin-rubricas/view', [
                            'model' => $model,
                            'modal' => $modal,
                ]);
            }
        }
    }

    /**
     * Finds the FinRubricas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FinRubricas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = FinRubricas::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = FinRubricas::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])) !== null) {
                return $model;
            }
        }

        throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
    }

}
