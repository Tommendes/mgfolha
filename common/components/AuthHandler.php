<?php

namespace common\components;

use common\models\Auth;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use frontend\controllers\SisEventsController;
use yii\helpers\Url;
use yii\web\Controller;
use yii\helpers\Json;
use frontend\controllers\AppController;
use frontend\controllers\UserController;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler extends Controller {

    /**
     * @var ClientInterface
     */
    private $client;
    public $return;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public function handle() {
        //Se o comando tiver aprtido da própria tela de usuário o controller volta à tela e não à home
        if (isset(Yii::$app->request->queryParams['r']) &&
                !empty(Yii::$app->request->queryParams['r']) &&
                Yii::$app->request->queryParams['r'] == '1') {
            $this->return = true;
        }
        $attributes = $this->client->getUserAttributes();
        $name = ArrayHelper::getValue($attributes, 'name');
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');

        /* @var Auth $auth */
        $auth = Auth::find()->where([
                    'source' => $this->client->getId(),
                    'source_id' => $id,
                ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var User $user */
                $user = User::findOne($auth->user_id);
                $this->updateUserInfo($user, $attributes);
                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            } else { // signup
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    Yii::$app->session->setFlash('error', Yii::t('yii', "User with the same email as in {client} account "
                                    . "already exists but isn't linked to it. Login using email "
                                    . "first to link it.", ['client' => $this->client->getTitle()])
                    );
                } else {
                    $user = new User([
                        'username' => $name,
                        'email' => $email,
                        'status' => User::STATUS_NEW_USER_RS,
                        'created_at' => time(),
                        'updated_at' => time(),
                    ]);
                    $user->generateAuthKey();
                    $user->setPassword($user->generateHash());
                    $user->generatePasswordResetToken();
                    $user->evento = SisEventsController::registrarEvento('Usuário inserido com sucesso. Meio utilizado: ' . $this->client->getId(), 'AuthHandler', $email, 'user');

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth($this->getAttributes($attributes, $user->id));
                        if ($auth->save()) {
                            AppController::saveFile($user, $auth->picture);
                            UserController::EmailBoasVindas($user);
                            $transaction->commit();
                            Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                            Yii::$app->session->setFlash('success', Yii::t('yii', 'UAL!!! It\'s so amazing to having you with us.<br>But before proceeding, please add '
                                            . 'information that we do not yet have in the form below. '
                                            . 'And if you want, you can still change your username. Then click the blue '
                                            . 'diskette at the top of the form'));
                            Yii::$app->session->setFlash('info', Yii::t('yii', 'Do not worry about access denied. This is already going to change... ;-)'));
//                            return $this->redirect(Url::home(true) . 'user/' . Yii::$app->user->identity->slug . '?mv=edit');
                            return $this->goHome();
                        } else {
                            Yii::$app->session->setFlash('error', Yii::t('yii', 'Unable to save {client} account: {errors}', [
                                        'client' => $this->client->getTitle(),
                                        'errors' => Json::encode($auth->getErrors())
                                    ])
                            );
                        }
                    } else {
                        Yii::$app->session->setFlash('info', Yii::t('yii', 'Oops, that\'s boring. It looks like another '
                                        . 'user already has the same name as you. But do not fret. '
                                        . 'You can register with your email and password and then you '
                                        . 'can link with your fecebook to use the buddy button. And if you want, '
                                        . 'please contact our support team.')
                        );
//                        Yii::$app->session->setFlash('error', Yii::t('yii', 'Unable to save user: {errors}', [
//                                    'client' => $this->client->getTitle(),
//                                    'errors' => AppController::limpa_json(Json::encode($user->getErrors()))
//                                ])
//                        );
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth($this->getAttributes($attributes));
                if ($auth->save()) {
                    /** @var User $user */
                    $user = User::findOne($auth->user_id);
                    $this->updateUserInfo($user, $attributes);
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Linked {client} account.', [
                                'client' => $this->client->getTitle()
                            ])
                    );
                    if ($this->return) {
//                        return $this->goBack();
                        return $this->redirect(Url::home(true) . 'user/' . Yii::$app->user->identity->slug);
                    }
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'Unable to link {client} account: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => Json::encode($auth->getErrors()),
                            ])
                    );
                }
            } else { // there's existing auth
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Unable to link {client} account. There is another user using it.', ['client' => $this->client->getTitle()]));
            }
        }
    }

    public function getAttributes($attributes, $user_id = null) {
        return [
            'user_id' => !is_null($user_id) ? $user_id : Yii::$app->user->id,
            'source' => $this->client->getId(),
            'source_id' => (string) $attributes['id'],
            'created_at' => time(),
            'updated_at' => time(),
            'status' => Auth::STATUS_ATIVO,
            'user' => (string) $attributes['name'],
            'cover' => (string) $attributes['cover']['source'],
            'email' => (string) $attributes['email'],
            'first_name' => (string) $attributes['first_name'],
            'last_name' => (string) $attributes['last_name'],
            'age_range' => (string) $attributes['age_range'],
            'link' => (string) $attributes['link'],
            'gender' => (string) $attributes['gender'],
            'locale' => (string) $attributes['locale'],
            'picture' => (string) $attributes['picture']['data']['url'],
            'timezone' => (string) $attributes['timezone'],
            'updated_time' => (string) $attributes['updated_time'],
            'verified' => (string) $attributes['verified'],
        ];
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user, $attributes) {
        $auth = Auth::findOne(['user_id' => $user->id])->delete();
        $auth = new Auth($this->getAttributes($attributes, $user->id));
        if ($auth->save()) {
            AppController::saveProfPhoto($user, $auth->picture);
        }
    }

}
