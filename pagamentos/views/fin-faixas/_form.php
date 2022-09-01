<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinFaixas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-faixas-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' : $model->slug) . ('?modal=' . $modal),
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

    <div class="row"> 
        <div class="col-md-3">
            <?= $form->field($model, 'tipo')->textInput(['placeholder' => $model->getAttributeLabel('tipo')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'data')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('data')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'faixa')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('faixa')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_final1')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_final1')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_faixa1')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_faixa1')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_deduzir1')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_deduzir1')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_final2')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_final2')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_faixa2')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_faixa2')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_deduzir2')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_deduzir2')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_final3')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_final3')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_faixa3')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_faixa3')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_deduzir3')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_deduzir3')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_final4')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_final4')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_faixa4')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_faixa4')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_deduzir4')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_deduzir4')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_final5')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_final5')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_faixa5')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_faixa5')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'v_deduzir5')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('v_deduzir5')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'deduzir_dependente')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('deduzir_dependente')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'salario_vigente')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('salario_vigente')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'inss_teto')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('inss_teto')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'inss_patronal')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('inss_patronal')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'inss_rat')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('inss_rat')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'inss_fap')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('inss_fap')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'rpps_patronal')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('rpps_patronal')]) ?>
        </div>

        <div class="col-md-3">
            <?= Html::label('', null, []) ?>
            <div class="form-group" style="padding-top: 9px;">
                <div class="btn-group d-flex" role="group">
                    <?=
                    Html::submitButton(Yii::t('yii', 'Save'), ['id' => $submit_cadas_id = Yii::$app->security->generateRandomString(8), 'class' => 'btn btn-success w-' . (isset($modal) && $modal ? '50' : '100')])
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
