<?php

namespace pagamentos\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use pagamentos\models\PasswordResetRequestForm;
use pagamentos\models\ResetPasswordForm;
use pagamentos\models\SignupForm;
use pagamentos\models\ContactForm;
use pagamentos\models\Suporte;
use yii\helpers\Url;
use common\components\AuthHandler;
use pagamentos\controllers\UserController;
use common\models\User;

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
        } else {
            $this->setUpdates();
            if (Yii::$app->user->identity->tp_usuario == 0) {
                return $this->redirect(Url::home(true) . 'user/' . Yii::$app->user->identity->slug);
            } else {
                return $this->render('index');
            }
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
        return $this->goHome();
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
        return $this->render('_apresentacao', []);
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

    /**
     * Executa atualizações no banco de dados após verificar se a atualização já não foi aplicada
     * @param type $c comando = pasta em @console/ onde serão armazenados os scripts executados
     */
    public function setUpdates($c = 'updates') {
        $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
        $scriptFolder = Yii::getAlias("@console/procedures/$c/pagamentos/");
        $clientFolder = Yii::getAlias("@console/updates/$cliente/");
        if (!file_exists($clientFolder)) {
            mkdir($clientFolder, 0755, true);
        }
        foreach ($this->getProcedures($c) as $script) {
            $scriptFrom = $scriptFolder . $script; 
            $scriptTo = $clientFolder . $script;
            if (!file_exists($scriptTo)) {
                if (copy($scriptFrom, $scriptTo)) {
                    // $command = 'mysql --host=' . Yii::$app->db->host
                    //         . ' --user=' . Yii::$app->db->username
                    //         . ' --password=\'' . Yii::$app->db->password . '\''
                    //         . ' --database=' . Yii::$app->$cliente->dbname
                    //         . ' --execute="SOURCE ' . $scriptTo . '"';
                    // $result = shell_exec($command);
                    // SisEventsController::registrarEvento("Banco de dados acaba de ser atualizado! Script: $script. $command", 'AtualizacaoBD', Yii::$app->user->identity->id);
                    // Yii::$app->session->setFlash('success', Yii::t('yii', "Seu banco de dados acaba de ser atualizado! Parabéns!"));
                } else {
                    $result = "Não foi possível criar: $scriptTo";
                    Yii::$app->session->setFlash('success', Yii::t('yii', "Não foi possível aplicar uma atualização!"));
                }
            }
        }
    }

    /**
     * Retorna a lista de procedures a executar no BD
     */
    public function getProcedures($c = 'updates') {
        return array_slice(scandir(Yii::getAlias("@console/procedures/$c/pagamentos/")), 2);
    }

}
