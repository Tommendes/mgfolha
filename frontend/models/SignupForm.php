<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {

    public $username;
    public $email;
    public $password; 
    public $matricula; 
    public $cpf;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['username', 'filter', 'filter' => 'trim'],
//            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('yii', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('yii', 'This email address has already been taken.')],
            ['cpf', 'filter', 'filter' => 'trim'],
            // ['cpf', 'required'],
            ['cpf', 'string', 'max' => 11, 'message' => Yii::t('yii', 'Informe apenas os números do CPF.')],
            ['matricula', 'filter', 'filter' => 'trim'],
            // ['matricula', 'required'],
            ['matricula', 'string', 'max' => 8],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(), 'uncheckedMessage' => 'Por favor, confirme que você não é um robô.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'password' => Yii::t('yii', 'Senha'),
            'username' => Yii::t('yii', 'Usuário'),
            'cpf' => Yii::t('yii', 'CPF'),
            'matricula' => Yii::t('yii', 'Matrícula'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->getErrors();
        }

        return User::newUser($this->cpf, $this->matricula, $this->email, $this->password, 'signup', $this->username);
    }

}
