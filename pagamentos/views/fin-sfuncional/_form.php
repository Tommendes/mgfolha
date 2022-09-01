<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncional */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<div class="fin-sfuncional-form">

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
            <?= $form->field($model, 'id_cad_servidores')->textInput(['placeholder' => $model->getAttributeLabel('id_cad_servidores')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'ano')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('ano')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'mes')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('mes')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'parcela')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('parcela')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'situacao')->textInput(['placeholder' => $model->getAttributeLabel('situacao')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'situacaofuncional')->textInput(['placeholder' => $model->getAttributeLabel('situacaofuncional')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_cad_cargos')->textInput(['placeholder' => $model->getAttributeLabel('id_cad_cargos')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_cad_centros')->textInput(['placeholder' => $model->getAttributeLabel('id_cad_centros')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_cad_departamentos')->textInput(['placeholder' => $model->getAttributeLabel('id_cad_departamentos')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_pccs')->textInput(['placeholder' => $model->getAttributeLabel('id_pccs')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'desconta_irrf')->textInput(['placeholder' => $model->getAttributeLabel('desconta_irrf')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'tp_previdencia')->textInput(['placeholder' => $model->getAttributeLabel('tp_previdencia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'desconta_sindicato')->textInput(['placeholder' => $model->getAttributeLabel('desconta_sindicato')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_anuenio')->textInput(['placeholder' => $model->getAttributeLabel('lanca_anuenio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_trienio')->textInput(['placeholder' => $model->getAttributeLabel('lanca_trienio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_quinquenio')->textInput(['placeholder' => $model->getAttributeLabel('lanca_quinquenio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_decenio')->textInput(['placeholder' => $model->getAttributeLabel('lanca_decenio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_salario')->textInput(['placeholder' => $model->getAttributeLabel('lanca_salario')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'lanca_funcao')->textInput(['placeholder' => $model->getAttributeLabel('lanca_funcao')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'n_faltas')->textInput(['placeholder' => $model->getAttributeLabel('n_faltas')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'decimo_aniv')->textInput(['placeholder' => $model->getAttributeLabel('decimo_aniv')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'n_horaaula')->textInput(['placeholder' => $model->getAttributeLabel('n_horaaula')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'n_adnoturno')->textInput(['placeholder' => $model->getAttributeLabel('n_adnoturno')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'n_hextra')->textInput(['placeholder' => $model->getAttributeLabel('n_hextra')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'ponto')->textInput(['placeholder' => $model->getAttributeLabel('ponto')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'categoria_receita')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('categoria_receita')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'previdencia')->textInput(['placeholder' => $model->getAttributeLabel('previdencia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'tipobeneficio')->textInput(['placeholder' => $model->getAttributeLabel('tipobeneficio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_beneficio')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_beneficio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'retorno_ocorrencia')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('retorno_ocorrencia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'retorno_data')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('retorno_data')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'retorno_valor')->textInput(['placeholder' => $model->getAttributeLabel('retorno_valor')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'retorno_documento')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('retorno_documento')]) ?>
        </div>

        <div class="col-md-3">
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
