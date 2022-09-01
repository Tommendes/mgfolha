<?php

namespace frontend\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\base\InvalidParamException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Suporte;
use yii\helpers\Url;
use common\components\AuthHandler;
use common\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\web\Response;
use common\models\User;
use kartik\helpers\Html;
use common\controllers\AppController;
use frontend\controllers\SisEventsController;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * Resto do token de usuário
     */
    const RESTO = 1;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
    public function actions()
    {
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
    public function actionIndex($token = null)
    {
        $this->setUpdates();
        if (!file_exists($assetsFolder = Yii::getAlias('@pagamentos/assets'))) {
            mkdir($assetsFolder, 0755, true);
        }
        if (!is_null($token)) {
            switch ($token) {
                    //                case 'gitRepo': $retorno = $this->render('gitRepo', ['token' => $token]);
                    //                    break;
                default:
                    $retorno = $this->actionHoleriteOnLine($token);
            }
            return $retorno;
        }
        if (Yii::$app->user->isGuest) {
            return $this->render('index', ['token' => $token]);
        } else {
            $servico = strtolower(Yii::$app->user->identity->base_servico);
            return $this->redirect(["/$servico"]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Setar o DB baseado na URL
     * @return type
     */
    public static function getDb()
    {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        return Yii::$app->$db;
    }

    public function actionHoleriteOnLine($token = null)
    {
        $model = new \frontend\models\Holerite(['token' => $token]);
        $modelValido = null;

        if ($model->load(Yii::$app->request->post()) || !is_null($model->token)) {
            if (strlen($model->token) <= 10) {
                $id = $model->token;
                return $this->redirect(["/ss/$id"]);
            }
            $newToken = explode('|', base64_decode($model->token));
            if (is_array($newToken) && count($newToken) == 6) {
                $model->ano = $ano = $newToken[0];
                $model->mes = $mes = $newToken[1];
                $model->parcela = $parcela = $newToken[2];
                $model->id_cad_servidor = $id_cad_servidor = $newToken[3];
                $model->dominio = $dominio = $newToken[4];
                $model->cliente = $cliente = $newToken[5];
                $url_root = 'https://mgfolha.com.br/holerite-on-line?token=';
                $bd = 'mgfolha_' . $cliente;
                $modelValido = $this->getDb()
                    ->createCommand("CALL $bd.get_holerites('$url_root','$dominio','$ano','$mes','$parcela','$id_cad_servidor','$id_cad_servidor')")
                    ->queryOne();
                //                $model->token = null;
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('holerite', [
                'model' => $model,
                'modelValido' => $modelValido,
            ]);
        } else {
            return $this->render('holerite', [
                'model' => $model,
                'modelValido' => $modelValido,
                'size' => 'sm',
            ]);
        }
        //        return $this->render('holerite', [
        //                    'model' => $model,
        //                    'modelValido' => $modelValido,
        //                    'size' => 'sm',
        //        ]);
    }

    /**
     * servidor-by-token
     * Localiza um servidor dentre os bancos de dados
     * @param type $id
     * @return type
     */
    public static function actionServidorByToken($id, $r = null)
    {
        $bancosDeDados = self::getDb()->createCommand("CALL mgfolha_folha.getBancosDeDados()")->query();
        $registros = [];
        foreach ($bancosDeDados as $bancoDeDados) {
            $bds = $bancoDeDados['bancosDeDados'];
            $clientes = $bancoDeDados['clientes'];
            $retornos[] = [
                'bancosDeDados' => $bds,
                'clientes' => $clientes,
                'cpf' => substr(base_convert($id, 36, 12), strlen(self::RESTO), 11),
                'matricula' => substr(base_convert($id, 36, 12), 12),
            ];
        }
        foreach ($retornos as $retorno) {
            $cpf = $retorno['cpf'];
            $matricula = $retorno['matricula'];
            $bancosDeDados = $retorno['bancosDeDados'];
            $sql = "select * from $bancosDeDados.cad_servidores "
                . "where cast(substring(matricula,1,4) as unsigned) = cast('$matricula' as unsigned) and "
                . "cast(cpf as unsigned) = cast('$cpf' as unsigned)";
            $locate = self::getDb()->createCommand($sql)->queryOne();
            // if ($locate) {
            $cliente = $retorno['clientes'];
            $registros[] = ['bancoDeDados' => $bancosDeDados, 'cliente' => $cliente, 'servidor' => $locate];
            // } else $registros = $sql;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($r) {
            return ($registros[0][$r]);
        } else {
            return ($registros);
        }
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelLogin = new LoginForm();
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
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('login', [
                'modelLogin' => $modelLogin,
            ]);
        } else {
            return $this->render('login', [
                'modelLogin' => $modelLogin,
                'size' => 'sm',
            ]);
        }
    }

    /**
     * Signs new user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $modelSignup = new SignupForm();
        if ($modelSignup->load(Yii::$app->request->post())) {
            //            if ($user = $modelSignup->signup() && $user instanceof common\models\User && Yii::$app->getUser()->login($user)) {
            //                Yii::$app->session->setFlash('success', Yii::t('yii', 'UAL!!! It\'s so amazing to having you with us.'));
            //                return $this->goHome();
            //            } else {
            ////                if (strlen($modelSignup->signup()) > 0) {
            //                    Yii::$app->session->setFlash('error', $modelSignup->signup());
            ////                }
            //                return $this->redirect(['/signup']);
            //            }
            if ($user = $modelSignup->signup()) {

                //            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                //            return $user;
                if (($user instanceof SignupForm || $user instanceof User) && Yii::$app->getUser()->login($user)) {
                    //                    UserController::EmailBoasVindas($user);
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'UAL!!! It\'s so amazing to having you with us.'));
                    return $this->goHome();
                } else if (($user instanceof SignupForm || $user instanceof User)) {
                    $userValidacao = '';
                    foreach ($user as $key => $resp) {
                        $userValidacao .= AppController::limpa_json($resp) . ' ';
                    }
                    Yii::$app->session->setFlash('error', AppController::limpa_json($userValidacao));
                    return $this->redirect(['/signup']);
                } else {
                    print_r($user);
                }
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('signup', [
                'modelSignup' => $modelSignup,
            ]);
        } else {
            return $this->render('signup', [
                'modelSignup' => $modelSignup,
                'size' => 'sm',
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout($m = 'Saída do sistema realizada com sucesso!', $p = null)
    {
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
    public function actionContato()
    {
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
    //    public function actionSignup() {
    //        $model = new SignupForm();
    //        if ($model->load(Yii::$app->request->post())) {
    //            if ($user = $model->signup()) {
    //                if (Yii::$app->getUser()->login($user)) {
    //                    UserController::EmailBoasVindas($user);
    //                    return $this->goHome();
    //                }
    //            }
    //        }
    //
    //        return $this->render('signup', [
    //                    'model' => $model,
    //        ]);
    //    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Check your email for further instructions.'));

                return $this->redirect(Url::home() . 'login#signin');
            } else {
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Sorry, we are unable to reset password for the provided email address. Error: {error}', ['error' => Json::encode($model->getErrors())]));
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
    public function actionResetPassword($token)
    {
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
    public function actionTermosUso($artigo = 'termos-uso')
    {
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
    public function actionPoliticaPrivacidade($artigo = 'politica-privacidade')
    {
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

    public function actionApresentacao()
    {
        return $this->render('_apresentacao', []);
    }

    public function actionUpload()
    {
        $model = new UploadForm();
        $data = $this->render('upload', ['model' => $model]);
        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                $data = Yii::t('yii', 'Successfully uploaded file.');
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
    public static function getFolhaAtual()
    {
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
    public function setUpdates($c = 'updates')
    {
        $scriptFolder = Yii::getAlias("@console/procedures/$c/base/");
        $clientFolder = Yii::getAlias("@console/updates/site/");
        if (!file_exists($clientFolder)) {
            mkdir($clientFolder, 0755, true);
        }
        foreach ($this->getProcedures($c) as $script) {
            $scriptFrom = $scriptFolder . $script;
            $scriptTo = $clientFolder . $script;
            if (!file_exists($scriptTo)) {
                if (copy($scriptFrom, $scriptTo)) {
                    $command = 'mysql --host=' . Yii::$app->db->host
                        . ' --user=' . Yii::$app->db->username
                        . ' --password=\'' . Yii::$app->db->password . '\''
                        . ' --database=' . Yii::$app->db->dbname
                        . ' --execute="SOURCE ' . $scriptTo . '"';
                    $result = shell_exec($command);
                    SisEventsController::registrarEvento("Banco de dados acaba de ser atualizado! Script: $script", 'AtualizacaoBD', (!Yii::$app->user->isGuest ? Yii::$app->user->identity->id : 0));
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
    public function getProcedures($c = 'updates')
    {
        return array_slice(scandir(Yii::getAlias("@console/procedures/$c/base/")), 2);
    }
}
