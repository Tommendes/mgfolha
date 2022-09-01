<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoUa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orgao-ua-form">

    <?php
    $cp = Yii::$app->request->queryParams['cp'];
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' :
                ($model->id)) . ('?modal=' . $modal . '&cp=' . $cp),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    if ($model->isNewRecord):
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
        $model->id_orgao = $cp;
    endif;
    ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'id_orgao')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?=
        Html::submitButton(Yii::t('yii', 'Save'), ['class' => 'btn btn-success'])
        . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                    'id' => 'closeAutoModalFooter',
                    'class' => 'btn btn-secondary',
                    'data-dismiss' => 'modal',
                    'aria-label' => 'Close',
                    'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                ]) : '')
        ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>

</div>
<?php
$idGridPJax = Yii::$app->controller->id . '_grid-pjax';
if (!$model->isNewRecord) {
    $js = <<< JS
    $('body').on('beforeSubmit', 'form#$idForm', function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
             return false;
        }
        // submit form
        $.ajax({
           url: form.attr('action'),
           type: 'post',
           data: form.serialize(),
           success: function (response) {
                PNotify.removeAll();
                new PNotify({ 
                    title: 'Sucesso', 
                    text: response,
                    type: 'success',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
                $("#closeAutoModalHeader").click();
                $.pjax.reload({container: '#$idGridPJax'});
           },
           error: function (response) {
                new PNotify({ 
                    title: 'Erro', 
                    text: response.responseText, 
                    type: 'warning',
                    styling: 'fontawesome',
                    nonblock: {
                        nonblock: false
                    },
                });
           }
        });
//        location.reload();
        return false;
    }); 
JS;
    $this->registerJs($js);
}
?>
