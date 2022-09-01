<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Acesso ao sistema';
?>

<div class="login form-transparence">
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>
    <a class="hiddenanchor" id="reset"></a>

    <div class="login_wrapper">

        <div class="animate form login_form form-bg">
            <section class="login_content">
                <?php
                $form = ActiveForm::begin(['id' => 'login-form', 'action' => Url::home(true) . 'login']);
                ?>

                <h1><?= Yii::t('yii', 'Log in') ?></h1>

                <?= $form->field($modelLogin, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Seu usuario ou email'])->label(false) ?>

                <?= $form->field($modelLogin, 'password')->passwordInput(['placeholder' => 'Sua senha'])->label(false) ?>

                <?= $form->field($modelLogin, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    <?= Html::a(Yii::t('yii', 'If you forgot your password you can reset it'), ['request-password-reset']) ?>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii', 'Log in'), ['class' => 'btn btn-warning', 'name' => 'login-button', 'style' => 'display: block; width: 100%;']) ?>
                    <!--<a class="btn btn-primary" style="display: block; width: 100%;" href="<?= Url::home(true) ?>auth?authclient=facebook"><?= Yii::t('yii', 'Log in with {clientOption}', ['clientOption' => 'Facebook']) ?></a>-->
                </div>

                <div class="clearfix"></div>

                <div class="separator">
                    <h1 class="change_link"><?= Yii::t('yii', 'New to site?') ?>
                        <a href="#signup" class="to_register"> <?= Yii::t('yii', 'Create Account') ?> </a>
                    </h1>

                    <div class="clearfix"></div>
                </div>
                <?php ActiveForm::end(); ?>
            </section>
        </div>

        <div id="register" class="animate form registration_form form-bg">
            <section class="login_content">
                <?php
                $form = ActiveForm::begin(['id' => 'form-signup', 'action' => Url::home(true) . 'login#signup']);
                ?>

                <h1><?= Yii::t('yii', 'Create Account') ?></h1>

                <?= $form->field($modelSignup, 'cpf')->textInput(['placeholder' => 'Seu CPF...'])->label(false); ?> 

                <?= $form->field($modelSignup, 'matricula')->textInput(['placeholder' => 'Sua matrícula de servidor...',])->label(false); ?>

                <?= $form->field($modelSignup, 'email')->textInput(['placeholder' => 'Seu email...', 'value' => isset(Yii::$app->request->queryParams['ei']) && !empty(Yii::$app->request->queryParams['ei']) ? Yii::$app->request->queryParams['ei'] : ''])->label(false); ?>

                <?= $form->field($modelSignup, 'password')->passwordInput(['placeholder' => 'Sua senha...'])->label(false); ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii', 'Create Account'), ['class' => 'btn btn-warning', 'name' => 'signin-button', 'style' => 'display: block; width: 100%;']) ?>
                    <!--<a class="btn btn-primary" style="display: block; width: 100%;" href="<?= Url::home(true) ?>auth?authclient=facebook"><?= Yii::t('yii', 'Create account with {clientOption}', ['clientOption' => 'Facebook']) ?></a>-->
                </div>

                <div class="clearfix"></div>

                <div class="separator">
                    <h1 class="change_link"><?= Yii::t('yii', 'Already a member?') ?>
                        <a href="#signin" class="to_register"> <?= Yii::t('yii', 'Log in') ?> </a>
                    </h1>

                    <div class="clearfix"></div>
                    <br />
                    <?php ActiveForm::end(); ?>
                </div>
            </section>
        </div>
    </div>
</div>
