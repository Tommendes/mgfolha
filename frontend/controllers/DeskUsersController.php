<?php

namespace frontend\controllers;

use Yii;
use frontend\models\DeskUsers;
use frontend\models\DeskUsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use frontend\controllers\SisEventsController;
use common\models\SisReviews;
use frontend\models\Msgs;

/**
 * DeskUsersController implements the CRUD actions for DeskUsers model.
 */
class DeskUsersController extends Controller
{

    public $gestor = 0;

    const CLASS_VERB_NAME = 'Usuário desktop';
    const CLASS_VERB_NAME_PL = 'Usuários desktop';
    const TIME_NOT_USING = 90;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        if (!Yii::$app->user->isGuest) {
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
                        'actions' => ['get-deskversao'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return true;
                        }
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && $this->gestor >= 1;
                        }
                    ],
                    [
                        'actions' => ['c', 'dpl'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['u'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                    [
                        'actions' => ['dlt'],
                        'allow' => true,
                        'matchCallback' => function () {
                            return !Yii::$app->user->isGuest && ($this->gestor >= 1);
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all DeskUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeskUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeskUsers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $modal = false, $mv = null)
    {
        $model_first = $this->findModel($id, true);
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post) && $model->save()) {
            $model_last = $this->findModel($id, true);
            //          registra evento no log do sistema
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName());
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully updated record.'));
            return $this->redirect(Url::home(true) . str_replace('_', '-', $model->tableName()) . '/' . $model->id);
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
     * Creates a new DeskUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionC($modal = false, $cp = null)
    {
        $model = new DeskUsers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //          gera evento para ser gravado no log do sistema 
            $model->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($model), 'actionC', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully created record.'));
            return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing DeskUsers model.
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
            $model->evento = SisEventsController::registrarUpdate($model_first, $model_last, isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName());
            $model->save();
            $data = Yii::t('yii', 'Successfully updated record.');
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $data;
            } else {
                Yii::$app->session->setFlash('success', $data);
                return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing DeskUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDlt($id)
    {
        $model = $this->findModel($id);

        //          registra evento no log do sistema
        $model->evento = SisEventsController::registrarEvento("Registro excluído com sucesso! Dados do registro excluído: " . SisEventsController::evt($model), 'actionDlt', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $model->tableName());
        if ($model->delete()) {
            $data = ['mess' => Yii::t('yii', 'Successfully deleted record'), 'class' => 'success', 'stat' => true];
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
     * Duplicates an existing DeskUsers model.
     * If duplication is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDpl($id)
    {
        $model = $this->findModel($id);
        $id_n = DeskUsers::find()->max('id') + 1;
        $model->id = $id_n;
        $model->status = DeskUsers::STATUS_ATIVO;
        $model->isNewRecord = true;
        if ($model->save()) {
            $model->evento = SisEventsController::registrarEvento("Registro duplicado com sucesso! "
                . "Dados do registro duplicado: "
                . SisEventsController::evt($model), 'actionDpl', Yii::$app->user->identity->username, $model->tableName(), $model->id);
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Successfully duplicated record.'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            Yii::$app->session->setFlash('warning', Yii::t('yii', 'Attempt to duplicate unsuccessful. Error: ') . Json::encode($model->getErrors()));
            return $this->redirect(['u', 'id' => $id]);
        }
    }

    /**
     * Finds the DeskUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeskUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $array = false)
    {
        if ($array) {
            if (($model = DeskUsers::find()
                ->where([
                    is_numeric($id) ? 'id' : 'slug' => $id,
                ])->asArray()->one()) !== null) {
                return $model;
            }
        } else {
            if (($model = DeskUsers::findOne([
                is_numeric($id) ? 'id' : 'slug' => $id,
            ])) !== null) {
                return $model;
            }
        }
        //        if (($model = DeskUsers::findOne([
        //                    is_numeric($id) ? 'id' : 'slug' => $id,
        //                ])) !== null) {
        //            return $model;
        //        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetDeskversao($dll_cod = null, $dll_cnpj = null, $dll_nome = null, $cli_cnpj = null, $cli_nome = null, $cli_nome_comput = null, $cli_nome_user = null, $versao_desk = null, $e_social = null, $formatResp = null)
    {
        $reviews = SisReviews::find()
            ->where(['status' => SisReviews::STATUS_ATIVO])
            ->orderBy('versao DESC,lancamento DESC,revisao DESC')
            ->limit(1)->one();
        $review = $reviews->versao . '.' . $reviews->lancamento . '.' . str_pad($reviews->revisao, 3, '0', STR_PAD_LEFT);
        $descricao = $reviews->descricao;
        if (($msgs = Msgs::find()
            ->join('join', 'desk_users', 'desk_users.id = msgs.id_desk_user')
            ->where([
                'dll_cod' => $dll_cod,
                'cli_nome_comput' => $cli_nome_comput,
                'cli_nome_user' => $cli_nome_user,
                'msgs.status' => Msgs::STATUS_ATIVO,
            ])
            ->orderBy('rand()')->asArray()->one()) === null) {
            $msgs = [
                'body' => '',
                'link' => '',
                'id' => '',
            ];
        }
        if (($usuario = DeskUsers::find()->where([
            'dll_cod' => $dll_cod,
            'cli_nome_comput' => $cli_nome_comput,
            'cli_nome_user' => $cli_nome_user,
        ])->one()) !== null) {
            $model_first = $this->findModel($usuario->id, true);
            $status = $usuario->status_desk;
            $usuario->dll_cod = $dll_cod;
            $usuario->dll_cnpj = $dll_cnpj;
            $usuario->dll_nome = $dll_nome;
            $usuario->cli_cnpj = $cli_cnpj;
            $usuario->cli_nome = $cli_nome;
            $usuario->cli_nome_comput = $cli_nome_comput;
            $usuario->cli_nome_user = $cli_nome_user;
            $usuario->versao_desk = $versao_desk;
            $usuario->e_social = $e_social;
            $usuario->updated_at = time();
            $usuario->save();
            $model_last = $this->findModel($usuario->id, true);
            $usuario->evento = SisEventsController::registrarUpdate($model_first, $model_last, isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $usuario->tableName());
            $usuario->save();
            //            if ($usuario->save()) {
            //                return 'atualizado em: ' . time();
            //            } else {
            //                return Json::encode($usuario->getErrors());
            //            }
        } else {
            $status = DeskUsers::STATUS_INATIVO;
            $usuario = new DeskUsers();
            $usuario->status = 10;
            $usuario->status_desk = $status;
            $usuario->dll_cod = $dll_cod;
            $usuario->dll_cnpj = $dll_cnpj;
            $usuario->dll_nome = $dll_nome;
            $usuario->cli_cnpj = $cli_cnpj;
            $usuario->cli_nome = $cli_nome;
            $usuario->cli_nome_comput = $cli_nome_comput;
            $usuario->cli_nome_user = $cli_nome_user;
            $usuario->versao_desk = $versao_desk;
            $usuario->e_social = $e_social;
            $usuario->updated_at = time();
            $usuario->save();
            $usuario->evento = SisEventsController::registrarEvento("Registro criado com sucesso! " . SisEventsController::evt($usuario), 'actionGetDeskversao', isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0, $usuario->tableName(), $usuario->id);
            if ($usuario->save()) {
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'newUserToAdm-html', 'text' => 'newUserToAdm-text'],
                        ['model' => $usuario]
                    )
                    ->setFrom([Yii::$app->params['noreplyEmail'] => 'Robô ' . Yii::$app->name])
                    ->setTo([Yii::$app->params['adminEmail'] => 'Tom Mendes',])
                    ->setSubject(Yii::t('yii', 'Novo usuário Desk') . Yii::t('yii', ' - Message express from ') . Yii::$app->name)
                    ->send();
            }
        }
        if ($formatResp == null) {
            $formatResp = Response::FORMAT_JSON;
        } else {
            $formatResp = Response::FORMAT_XML;
        }
        Yii::$app->response->format = $formatResp;
        return ([
            'status_desk' => $status,
            'versao' => $review,
            'razao' => $descricao,
            'body' => $msgs['body'],
            'link' => $msgs['link'],
            'id_msgs' => $msgs['id'],
        ]);
    }
}
