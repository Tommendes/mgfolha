<?php

namespace cash\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use cash\models\PasswordResetRequestForm;
use cash\models\ResetPasswordForm;
use cash\models\SignupForm;
use cash\models\ContactForm;
use cash\models\Suporte;
use yii\helpers\Url;
use common\components\AuthHandler;
use common\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;
use cash\controllers\UserController;
use common\models\User;
use kartik\helpers\Html;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/../']);
        } else if (!Yii::$app->user->isGuest && Yii::$app->user->identity->status === User::STATUS_NEW_USER) {
            return $this->redirect(
                            Url::home(true) . 'user/boas-vindas?r=' . Yii::$app->user->identity->auth_key
            );
        } else {
            return $this->render('index');
        }
    }

    public function onAuthSuccess($client) {
        (new AuthHandler($client))->handle();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLogin = new LoginForm();
        $modelSignup = new SignupForm();
        if ($modelLogin->load(Yii::$app->request->post())) {
            if ($modelLogin->login()) {
                $usuario = User::findOne(Yii::$app->user->identity->id);
                SisEventsController::registrarEvento("Login realizado com sucesso! ", 'actionLogin', Yii::$app->user->identity->id, 'actionLogin', Yii::$app->user->identity->id);
                if (isset($usuario->l_url) && !$usuario->l_url == null) {
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Bem vindo de volta. Gostaria de continuar de onde parou? Clique {aqui}', ['aqui' => Html::a('aqui', $usuario->l_url)]));
                    $usuario->l_url = null;
                    $usuario->save();
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Bem vindo de volta.'));
                }
            } else if (!$modelLogin->login()) {
                Yii::$app->session->setFlash('warning', Yii::t('yii', 'Sorry, we did not find the data you entered. Try again. Check the username and password entered.'));
                Yii::$app->session->setFlash('info', Yii::t('yii', 'If you think this is a bug, contact your system administrator.'));
            }
            return $this->goBack();
        } else if ($modelSignup->load(Yii::$app->request->post())) {
            if ($user = $modelSignup->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    UserController::EmailBoasVindas($user);
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'UAL!!! It\'s so amazing to having you with us.'));
                    return $this->goHome();
                } else {
                    return $this->redirect('http://localhost/folha/login#signup');
                }
            }
        }
        return $this->render('login', [
                    'modelLogin' => $modelLogin,
                    'modelSignup' => $modelSignup,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout($m = 'Saída do sistema realizada com sucesso!', $p = null) {
        $user = Yii::$app->user;
//        if ($p == null) {
//            $p = Url::current();
//        }
        $usuario = User::findOne($user->id);
        $usuario->l_url = $p;
        $usuario->save();
        SisEventsController::registrarEvento($m, 'actionLogout', $user->id, 'actionLogout', $user->id);
        if ($user->logout()) {
            Yii::$app->session->setFlash('info', $m);
        }
        return $this->gohome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContato() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('yii', 'There was an error sending your message.'));
            }

            return $this->refresh();
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('contact', [
                            'model' => $model,
                ]);
            } else {
                return $this->render('contact', [
                            'model' => $model,
                ]);
            }
        }
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    UserController::EmailBoasVindas($user);
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Check your email for further instructions.'));

                return $this->redirect(Url::home() . 'login#signin');
            } else {
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('yii', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Displays termos de uso.
     *
     * @return mixed
     */
    public function actionTermosUso($artigo = 'termos-uso') {
        $model = Suporte::findOne([
                    is_numeric($artigo) ? 'id' : 'slug' => $artigo,
        ]);
        if ($model !== null) {
            return $this->render('/suporte/artigos', [
                        'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist'));
        }
    }

    /**
     * Displays política de privacidade.
     *
     * @return mixed
     */
    public function actionPoliticaPrivacidade($artigo = 'politica-privacidade') {
        $model = Suporte::findOne([
                    is_numeric($artigo) ? 'id' : 'slug' => $artigo,
        ]);
        if ($model !== null) {
            return $this->render('/suporte/artigos', [
                        'model' => $model,
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist'));
        }
    }

    public function actionApresentacao() {
        return $this->render('_apresentacao', [
        ]);
    }

    public function actionUpload() {
        $model = new UploadForm();
        $data = $this->render('upload', ['model' => $model]);
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                $data = Yii::t('yii', 'Successfully uploaded file');
            } else {
                $data = Yii::t('yii', 'Erro: {erro}', ['erro' => Json::encode($model->getErrors())]);
            }
        }
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
        } else {
            Yii::$app->session->setFlash('success', $data);
        }
        return $data;
    }

    /**
     * Retorna a folha atual
     * @return string
     */
    public static function getFolhaAtual() {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->user->identity->per_ano . '/' . Yii::$app->user->identity->per_mes . '/' . Yii::$app->user->identity->per_parcela;
        } else {
            return '';
        }
    }

}
