<?php

use yii\widgets\ActiveForm;
use kartik\file\FileInput;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?=
$form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
    'options' => [
        'accept' => 'pdf, png, jpg, jpeg, zip, rar',
        'multiple' => true,
    ],
    'language' => 'pt',
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg', 'png'],
        'overwriteInitial' => false,
        'maxFileSize' => 1032,
        'showPreview' => true,
        'showCancel' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => true,
        'maxFileCount' => 10,
    ],
]);
?>
<?= Html::label('Formatos aceitos: pdf, png, jpg, jpeg, zip, rar', ['class' => 'control-label']) ?>

<button>Submit</button>

<?php ActiveForm::end() ?>