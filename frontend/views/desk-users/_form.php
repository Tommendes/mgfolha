<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeskUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="desk-users-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' : $model->id) . ('?modal=' . $modal),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    if ($model->isNewRecord):
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
    endif;
    ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <div class="row"> 
        <div class="col-md-3">
            <?= $form->field($model, 'status_desk')->textInput(['placeholder' => $model->getAttributeLabel('status_desk')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'dll_cod')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('dll_cod')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'dll_cnpj')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('dll_cnpj')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'dll_nome')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('dll_nome')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'cli_cnpj')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('cli_cnpj')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'cli_nome')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('cli_nome')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'cli_nome_comput')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('cli_nome_comput')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'cli_nome_user')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('cli_nome_user')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'versao_desk')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('versao_desk')]) ?>
        </div>

        <div class="col-md-3">
            <?= Html::label('', null, []) ?>
            <div class="form-group" style="padding-top: 9px;">
                <div class="btn-group d-flex" role="group">
                    <?=
                    Html::submitButton(Yii::t('yii', 'Save'), ['id' => $submit_cadas_id = Yii::$app->security->generateRandomString(8), 'class' => 'btn btn-success w-' . (isset($modal) && $modal ? '50' : '100')])
                    . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                                'id' => 'closeAutoModalFooter',
                                'class' => 'btn btn-default w-50',
                                'data-dismiss' => 'modal',
                                'aria-label' => 'Close',
                                'title' => 'Fechar janela(Fechar sem salvar causará perda de dados)',
                            ]) : '')
                    ?>
                </div>
            </div>
        </div>
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
