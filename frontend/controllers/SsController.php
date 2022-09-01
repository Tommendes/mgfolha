<?php

namespace frontend\controllers;

use Yii;
use common\models\SisShortener;
use common\models\SisShortenerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Url;

/**
 * SsController implements the CRUD actions for SisShortener model.
 */
class SsController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
                    ['actions' => ['view',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    ['actions' => ['short',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest;
                        }
                    ],
                    ['actions' => ['index',],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->administrador >= 1;
                        }
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->session->setFlash('info', Yii::t('yii', 'Função indisponível para seu perfil de usuário!'));
                    return $this->goHome();
                }
            ],
        ];
    }

    /**
     * Lists all SisShortener models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SisShortenerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisShortener model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        if (($model = SisShortener::find()->where(['shortened' => $id])->one()) != null) {
            return $this->redirect($model->url);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Gera Url Encurtada
     * @param type $url
     * @return type
     */
    public function actionShort($url, $f = true) {
        return self::short($url, $f);
    }

    /**
     * Finds the SisShortener model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SisShortener the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = SisShortener::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Gera Url Encurtada
     * @param type $url Url a encurtar
     * @param type $f Retora a Url completa: dominio + url curta?
     * @return string
     */
    public static function short($url, $f = true) {
        if (($model = SisShortener::findOne(['url' => $url])) == null) {
            // Caso não haja uma url no BD então cria o shortened
            $model = new SisShortener();
            $model->url = $url;
            if (!$model->save()) {
                // Em caso de erro retorna nulo
                return 'Erro ao tentar encurtar URL';
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($f == true) {
            return ['retorno' => Url::home(true) . 'ss/' . $model->shortened];
        } else {
            return ['retorno' => $model->shortened];
        }
    }

}
