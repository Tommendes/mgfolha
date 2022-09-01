<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */ 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Acesso ao sistema';
?> 

<div class="signup">
    <div class="<?= isset($size) && !empty($size) && $size == 'sm' ? 'offset-md-4 col-md-4 ' : '' ?>form-bg">
        <?php
        $form = ActiveForm::begin(['id' => 'form-signup', 'action' => Url::home(true) . 'signup']);
        ?>

        <h1><?= Yii::t('yii', 'Create Account') ?></h1>

        <?= $form->field($modelSignup, 'cpf')->textInput(['placeholder' => 'Seu CPF...'])->label(false); ?> 

        <?= $form->field($modelSignup, 'matricula')->textInput(['placeholder' => 'Sua matrícula de servidor...',])->label(false); ?>

        <?= $form->field($modelSignup, 'email')->textInput(['placeholder' => 'Seu email...', 'value' => isset(Yii::$app->request->queryParams['ei']) && !empty(Yii::$app->request->queryParams['ei']) ? Yii::$app->request->queryParams['ei'] : ''])->label(false); ?>

        <?= $form->field($modelSignup, 'password')->passwordInput(['placeholder' => 'Sua senha...'])->label(false); ?>

        <?= $form->field($modelSignup, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(), ['widgetOptions' => ['id' => Yii::$app->security->generateRandomString(8)]])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('yii', 'Create Account'), ['class' => 'btn btn-warning', 'name' => 'signin-button', 'style' => 'display: block; width: 100%;']) ?>
        </div>

        <div class="clearfix"></div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
