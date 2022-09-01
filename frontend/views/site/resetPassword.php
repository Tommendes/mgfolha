<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \s\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('yii', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password ">

    <div class="login_wrapper">

        <div class="animate form login_form form-transparence">
            <section class="login_content">

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <h1><?= $this->title ?></h1>

                <p class="text-center"><?= Yii::t('yii', 'Please choose your new password:') ?></p>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>
