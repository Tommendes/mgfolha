<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSmovimentacao */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<div class="cad-smovimentacao-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' : $model->slug) . ('?modal=' . $modal . '&id_cad_servidores=' . $model->id_cad_servidores),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    if ($model->isNewRecord):
        $model->dominio = Yii::$app->user->identity->dominio;
        $model->slug = Yii::$app->security->generateRandomString();
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
    endif;
    ?>
    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'id_cad_servidores')->hiddenInput()->label(false) ?>

    <div class="row"> 

        <div class="col-md-12">
            <?php
            $id_ca = Yii::$app->security->generateRandomString(8);
            $id_md = Yii::$app->security->generateRandomString(8);
            $id_cr = Yii::$app->security->generateRandomString(8);
            $id_dr = Yii::$app->security->generateRandomString(8);
            $id_da = Yii::$app->security->generateRandomString(8);

            echo $form->field($model, 'codigo_afastamento')->widget(Select2::classname(), [
                'data' => common\models\Listas::getCodsAfastamento(),
                'options' => [
                    'id' => $id_ca,
                    'placeholder' => 'Selecione...',
                    'multiple' => false,
                    'onchange' =>
                    "
                        $('#$id_da').val(dataFormatada());
                        if($('#$id_ca').val() == 'Q1' && $('#$id_da').val().length >= 10){
                            $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
                            $('#$id_dr').prop('readonly', true);
                        } else {
                            $('#$id_dr').val(null).change();
                            $('#$id_dr').removeAttr('readonly'); 
                        }
                        if($('#$id_ca').prop('selectedIndex')>3){
                            $('#$id_md').removeAttr('disabled'); 
                            $('#$id_dr').prop('readonly', true);
                        } else {
                            $('#$id_md').val(null).change();
                            $('#$id_md').prop('disabled', true);  
                        } 
                    ",
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])
            ?>
        </div>

        <div class="col-md-12">
            <?=
            $form->field($model, 'motivo_desligamentorais')->widget(Select2::classname(), [
                'data' => common\models\Listas::getCodsDesligamento(),
                'readonly' => $model->isNewRecord,
                'options' => [
                    'id' => $id_md,
                    'placeholder' => 'Selecione...',
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'id' => $id_md . '_po_' . Yii::$app->security->generateRandomString(8),
                    'allowClear' => true,
                ],
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'd_afastamento')->widget(MaskedInput::classname(), [
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'id' => $id_da,
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('d_afastamento') . ' dd/mm/aaaa',
                    'onblur' => "   
                        if($('#$id_ca').val() == 'Q1'){
                            $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
                            $('#$id_dr').prop('readonly', true);
                        } else {
                            $('#$id_dr').val(null).change();
                            $('#$id_dr').removeAttr('readonly'); 
                        }
                    ",
                ],
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'd_retorno')->widget(MaskedInput::classname(), [
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'readonly' => $model->isNewRecord,
                    'id' => $id_dr,
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('d_retorno') . ' dd/mm/aaaa',
                ],
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?= Html::label('', null, []) ?>
            <div class="form-group" style="padding-top: 9px;">
                <div class="btn-group d-flex" role="group">
                    <?=
                    (!$sf ? '' : Html::submitButton(Yii::t('yii', 'Save'), ['id' => $submit_cadas_id = Yii::$app->security->generateRandomString(8), 'class' => 'btn btn-success w-' . (isset($modal) && $modal ? '50' : '100')]))
                    . ((isset($modal) && $modal) ? Html::button('Fechar janela&nbsp;×', [
                                'id' => 'closeAutoModalFooter',
                                'class' => 'btn btn-secondary w-50',
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
$js = <<< JS
    if($('#$id_ca').prop('selectedIndex')>0){
        $('#$id_dr').removeAttr('readonly'); 
    }
    if($('#$id_ca').val() == 'Q1' && $('#$id_da').val().length >= 10){
        $('#$id_dr').val(SomarData( $('#$id_da').val(), 120, 0, true));
        $('#$id_dr').prop('readonly', true);
    } else {
        $('#$id_dr').val(null).change();
        $('#$id_dr').removeAttr('readonly'); 
    }
    if($('#$id_ca').prop('selectedIndex')>3){
        $('#$id_md').removeAttr('disabled'); 
        $('#$id_dr').prop('readonly', true);
    } else {
        $('#$id_md').val(null).change();
        $('#$id_md').prop('disabled', true);
    } 
JS;
$this->registerJs($js);
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
