<?php
/* @var $this yii\web\View */ 
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Acesso ao sistema';
?>

<div class="login">

    <div class="<?= isset($size) && !empty($size) && $size == 'sm' ? 'offset-md-4 col-md-4 ' : '' ?>form-bg">
        <?php
        $form = ActiveForm::begin(['id' => 'login-form', 'action' => Url::home(true) . 'login']);
        ?>

        <h1><?= Yii::t('yii', 'Log in') ?></h1>

        <?= $form->field($modelLogin, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Seu usuario ou email'])->label(false) ?>

        <?= $form->field($modelLogin, 'password')->passwordInput(['placeholder' => 'Sua senha'])->label(false) ?>

        <?= $form->field($modelLogin, 'rememberMe')->checkbox() ?>

        <?= $form->field($modelLogin, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(), ['widgetOptions' => ['id' => Yii::$app->security->generateRandomString(8)]])->label(false) ?>

        <div style="color:#999;margin:1em 0">
            <?= Html::a(Yii::t('yii', 'If you forgot your password you can reset it'), ['request-password-reset']) ?>.
        </div>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('yii', 'Log in'), ['class' => 'btn btn-warning', 'name' => 'login-button', 'style' => 'display: block; width: 100%;']) ?>
        </div>

        <div class="clearfix"></div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
