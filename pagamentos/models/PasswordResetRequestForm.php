<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {

    public $email;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
//                'filter' => ['status' => ['or',
//                    'status=' . User::STATUS_ATIVO,
//                    'status=' . User::STATUS_NEW_USER,
//                    'status=' . User::STATUS_NEW_USER_UNREGISTERED
//                ]],
                'message' => Yii::t('yii', 'There is no user with such email.'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail() {
        /* @var $user User */
        $user = User::find()
                ->where([
                    'email' => $this->email,
                ])
                ->andWhere(['or',
                    'status=' . User::STATUS_ATIVO,
                    'status=' . User::STATUS_NEW_USER,
                    'status=' . User::STATUS_NEW_USER_UNREGISTERED
                ])
                ->one();

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
        }

        if (!$user->save()) {
            return false;
        }

        return Yii::$app
                        ->mailer
                        ->compose(
                                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['user' => $user]
                        )
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($this->email)
                        ->setSubject(Yii::t('yii', 'Password reset for ') . Yii::$app->name)
                        ->send();
    }

}
