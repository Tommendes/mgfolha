<?php

namespace frontend\controllers;

use Yii;
use kartik\helpers\Html;
use common\models\SisReviews;
use common\models\SisReviewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use common\models\User;
use common\models\UserDomains;

/**
 * SisReviewsController implements the CRUD actions for SisReviews model.
 */
class SisReviewsController extends Controller {

    public $gestor = 0;
    public $administrador = 0;

    const CLASS_VERB_NAME = 'Revisão do sistema';
    const CLASS_VERB_NAME_PL = 'Revisões do sistema';

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        if (!Yii::$app->user->isGuest) {
            $this->gestor = Yii::$app->user->identity->gestor;
            $this->administrador = Yii::$app->user->identity->administrador;
        }
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'dlt' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['actions' => ['reviews'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    ['actions' => ['send-email'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->administrador;
                        }
                    ],
                    ['actions' => ['view', 'index', 'create', 'dpl',
                            'u', 'dlt', 'sis-reviews'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return $this->administrador >= 1;
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->redirect(['reviews']);
                }
            ],
        ];
    }

    /**
     * Lists all SisReviews models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new SisReviewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SisReviews model.
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
            return $this->redirect(Url::home(true) . 'sis-reviews/' . ($modal ? '' : $model->slug));
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
     * Creates a new SisReviews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null) {
        $model = new SisReviews();

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
     * Updates an existing SisReviews model.
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
     * Deletes an existing SisReviews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id) {
        $model = $this->findModel($id);
//        $this->findModel($id)->delete(); 

        $model->status = SisReviews::STATUS_CANCELADO;
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
     * Duplicates an existing SisReviews model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id) {
        $model = $this->findModel($id);
        $id_n = SisReviews::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = SisReviews::STATUS_ATIVO;
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
     * Finds the SisReviews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CadSmovimentacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false) {
        if ($array) {
            if (($model = SisReviews::find()
                            ->where([
                                is_numeric($id) ? 'id' : 'slug' => $id,
                            ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = SisReviews::findOne([
                        is_numeric($id) ? 'id' : 'slug' => $id,
                    ])) !== null) {
                return $model;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Displays a single SisReviews model.
     * @param integer $desk web = 0 ou desktop = 1
     * @return mixed
     */
    public function actionReviews($desk = 0) {
        $searchModel = SisReviewsSearch::find()
                ->where(['status' => SisReviews::STATUS_ATIVO])
                ->orderBy(['versao' => SORT_DESC, 'lancamento' => SORT_DESC, 'revisao' => SORT_DESC])
                ->all();
        $versao = SisReviews::find()->max('versao');
        $lancamento = SisReviews::find()
                ->where(['>=', 'versao', $versao])
                ->max('lancamento');
        $revisao = SisReviews::find()
                ->where(['>=', 'versao', $versao])
                ->andWhere(['>=', 'lancamento', $lancamento])
                ->max('revisao');

        if ($desk == 1) {
            return $this->render('reviews_desk', [
                        'searchModel' => $searchModel,
                        'versaoAtual' => $versao . '.' . $lancamento . '.' . $revisao,
            ]);
        } else {
            return $this->render('reviews');
        }
    }

    /**
     * Sends an email with a link, for Review registered.
     *
     * @return boolean whether the email was send
     */
    public function actionSendEmail($id) {
        $review = SisReviews::findOne([
                    is_numeric($id) ? 'id' : 'slug' => $id,
        ]);
        $users = User::findAll(['status' => User::STATUS_ATIVO]);
        $enviados = null;
        foreach ($users as $user) {
            $email = Yii::$app
                    ->mailer
                    ->compose(
                            ['html' => 'newReview-html', 'text' => 'newReview-text'], ['review' => $review, 'user' => $user]
                    )
                    ->setFrom([Yii::$app->params['noreplyEmail'] => 'Atualização ' . Yii::$app->name])
                    ->setTo([$user->email => $user->username])
                    ->setSubject(Yii::t('yii', 'System update') . Yii::t('yii', ' - Message express from ')
                    . Yii::$app->name . ' - ' . Html::encode(substr(sha1(time() . $user->username), 2, 9)));
            $enviados .= '&nbsp;<span class="badge badge-success">' . $user->email . '</span>';
            $email->send();
        }
        Yii::$app->session->setFlash('success', '<div class="row">'
                . '<div class="col-md-12">' . Yii::t('yii', 'Email enviado com sucesso para o(s) destinatário(s):') . '</div>'
                . '<div class="col-md-12">' . $enviados . '</div>'
                . '</div>'
        );
        return $this->redirect(Url::home(true) . 'sis-reviews/' . $review->slug);
    }

    /**
     * Retorna o domímio e razão social do domínio
     * @param type $id
     * @return type
     */
    public static function getDominio($id = null) {
        if (($dominio = UserDomains::find()
                ->select([
                    'dominio' => 'concat(cliente, ": ", dominio)'
                ])
                ->where(['cliente' => $id])
                ->one()) === null) {
            $dominio = ['dominio' => Yii::$app->name];
        }
        return $dominio;
    }

    /**
     * Retorna os domíios registrados no BD
     * @return type
     */
    public static function getDominios() {
        $dominio = UserDomains::find()
                ->select([
                    'id' => 'id',
                    'base_servico' => 'base_servico',
                    'cliente' => 'cliente',
                    'dominio' => 'dominio'
                ])
                ->groupBy('cliente, dominio')
                ->all();
        $dominio[] = ['base_servico' => Yii::$app->name, 'cliente' => '', 'dominio' => Yii::$app->id,];
        return $dominio;
    }

}
