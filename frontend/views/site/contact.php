<?php

use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use dosamigos\tinymce\TinyMce;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

$this->title = Yii::t('yii', 'Contact');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact form-transparence">

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-success">
            <?php echo Yii::t('yii', 'Thank you for contacting us. We will respond to you as soon as possible.'); ?>
        </div>

    <?php else: ?>


        <div class="row">
            <div class="col-xs-12 col-md-12">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'contact-form',
                            'action' => Url::home(true) . 'contato'
                ]);
                ?>

                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <p class="text-danger text-uppercase text-center">
                    <?php echo Yii::t('yii', 'Legal que você está aqui!<br />Por favor deixe-nos saber como podemos te ajudar.'); ?>
                </p>
                <div class="row">
                    <div class="col-xs-12 col-md-4">                        
                        <?=
                        $form->field($model, 'name')->textInput(['placeholder' => 'Seu nome',
                            'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->username : ''
                        ])->label(false);
                        ?>
                    </div>
                    <div class="col-xs-12 col-md-4">                        
                        <?php
                        echo $form->field($model, 'email')->textInput(['placeholder' => 'Seu email',
                            'value' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->email : ''
                        ])->label(false);
                        ?>
                    </div> 
                    <div class="col-xs-12 col-md-4">                        
                        <?= $form->field($model, 'subject')->dropDownList(['Suporte', 'Comercial', 'Financeiro', 'Sugestões', 'Reclamações'])->label(false) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12">                        
                        <?=
                        $form->field($model, 'body')->widget(TinyMce::className(), [
                            'language' => 'pt_BR',
                            'clientOptions' => [
                                'plugins' => [
                                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                                    'insertdatetime media nonbreaking save table contextmenu directionality',
                                    'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
                                ],
                                'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | codesample helpimage",
//                            'toolbar2' => ""
                            ],
                            'options' => [
                                'rows' => 5,
                                'id' => Yii::$app->security->generateRandomString(),
                            ],
                        ])->label('Mensagem')
                        ?>
                    </div>
                </div>

                <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(), ['widgetOptions' => ['id' => Yii::$app->security->generateRandomString(8)]])->label(false) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('yii', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif; ?>            
</div>
