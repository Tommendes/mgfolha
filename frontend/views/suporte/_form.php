<?php

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisSuporte */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sis-suporte-form">

    <div class="row">
        <div class="col-md-12">
            <?php $form = ActiveForm::begin(); ?>

            <?php echo $form->field($model, 'slug')->hiddenInput(['value' => strtolower(sha1($model->tableName() . time()))])->label(false) ?>

            <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'grupo')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

            <?=
            $form->field($model, 'descricao')->widget(TinyMce::className(), [
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
                    'rows' => 12,
                    'id' => Yii::$app->security->generateRandomString(),
                ],
            ])
            ?>

            <?= $form->field($model, 'texto')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'status')->textInput() ?>

            <?= $form->field($model, 'evento')->textInput() ?>

            <?= $form->field($model, 'created_at')->textInput() ?>

            <?= $form->field($model, 'updated_at')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('yii', 'Save Create') : Yii::t('yii', 'Save Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
$homeUrl = Url::home(true);
$js = <<< JS
JS;
$this->registerJs($js);
?>
