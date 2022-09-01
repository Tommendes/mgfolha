<?php

use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\helpers\Html;

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?=
$form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
    'options' => [
        'accept' => 'image/*',
        'multiple' => false,
    ],
    'language' => 'pt',
    'pluginOptions' => [
        'allowedFileExtensions' => ['jpg', 'png'],
        'overwriteInitial' => false,
        'maxFileSize' => 2048,
        'showPreview' => true,
        'showCancel' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => true,
    ],
]);
?>
<?= Html::label('Formatos aceitos: png, jpg, jpeg', ['class' => 'control-label']) ?>
<br>
<?=
(!$sf ? '' : Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success']))
 . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
            'id' => 'closeAutoModalFooter',
            'class' => 'btn btn-secondary',
            'data-dismiss' => 'modal',
            'aria-label' => 'Close',
            'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
        ]) : '');
?>

<?php ActiveForm::end() ?>