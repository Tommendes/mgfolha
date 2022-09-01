<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $rememberMe = true;
    private $_user;
    public $reCaptcha;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [ 
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username', 'email'], 'unique'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator2::className(), 'uncheckedMessage' => 'Por favor, confirme que você não é um robô.'],
        ];
    }

    /**
     * @inheritdoc  
     */
    public function attributeLabels() {
        return [
            'username' => Yii::t('yii', 'Usuário ou Email'),
            'password' => Yii::t('yii', 'Senha'),
            'rememberMe' => Yii::t('yii', 'Lembrar-me da próxima vez'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('yii', 'Incorrect or invalid username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->getUser() != null) {//$this->validate() && 
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ?
                            Yii::$app->params['user.rememberMeDuration'] : (3600/* uma hora */));
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

}
