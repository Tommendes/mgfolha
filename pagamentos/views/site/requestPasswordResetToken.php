<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \s\models\PasswordResetRequestForm */

use kartik\helpers\Html;
use kartik\form\ActiveForm;

$this->title = Yii::t('yii', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset ">

    <div class="login_wrapper">

        <div class="animate form login_form form-transparence">
            <section class="login_content">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <h1><?= $this->title ?></h1>

                <p class="text-center"><?= Yii::t('yii', 'Please fill out your email. A link to reset password will be sent there.') ?></p>

                <?php
                echo $form->field($model, 'email')->textInput(['type' => 'email', 'class' => 'form-control', 'autofocus' => true,
                    'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->email : '']);
                ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii', 'Send'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>
