<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \s\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

//$this->title = 'Iniciar Busca';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">

    <div class="row">
        <?php
        $index = true;
        if (Yii::$app->controller->id == 'pt' && Yii::$app->controller->action->id == 'signup') {
            $index = false;
        }
        ?>
        <div class="<?= !$index ? 'col-md-offset-4 col-md-4 form-op83' : 'col-md-12' ?>">
            <h1 class="text-center" style="color: <?= !$index ? '000' : '#fff' ?>"><?= Html::encode('Começar a usar') ?></h1>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'form-signup',
                        'action' => Url::home(true) . 'pt/signup']);
            ?>

            <?=
                    $form->field($model, 'username')->textInput([
                        'placeholder' => 'Seu CPF...',
                        'inline' => true,
//                        'autofocus' => true,
                        'class' => 'input-lg',
                        'style' => ['display' => 'block', 'width' => '100%'],
                    ])
                    ->label(false);
            ?> 

            <?=
                    $form->field($model, 'email')->textInput([
                        'placeholder' => 'Sua matrícula de servidor...',
                        'inline' => true,
                        'class' => 'input-lg',
                        'style' => ['display' => 'block', 'width' => '100%'],
                    ])
                    ->label(false);
            ?>

            <?=
                    $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Sua senha...',
                        'inline' => true,
                        'class' => 'input-lg',
                        'style' => ['display' => 'block', 'width' => '100%'],
                    ])
                    ->label(false);
            ?>

            <div class="form-group center-block">
                <div class="btn-group btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-warning" name="signup-button" style="display: block; width: 100%;">Novo usuário</button>
                    </div>
                    <!--                    <div class="btn-group" role="group">
                                            <a class="btn btn-danger google" href="<?php // echo Url::home(true)                              ?>pt/auth?authclient=google">Google</a>
                                        </div>-->
                    <div class="btn-group" role="group">
                        <a class="btn btn-primary facebook " href="<?= Url::home(true) ?>pt/auth?authclient=facebook">Facebook</a>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
