<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model {

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'verifyCode' => Yii::t('yii', 'Verification Code'),
        ];
    }

    function getSubjLabel($id) {
        switch ($id) {
            case 0: $ret = 'Suporte';
                break;
            case 1: $ret = 'Comercial';
                break;
            case 2: $ret = 'Financeiro';
                break;
            case 3: $ret = 'Sugestões';
                break;
            case 4: $ret = 'Reclamações';
                break;
        }
        return $ret;
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email) {
        return Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setFrom([$this->email => $this->name])
                        ->setSubject($this->getSubjLabel($this->subject))
                        ->setTextBody($this->body)
                        ->setHtmlBody($this->body)
                        ->send();
    }

}
