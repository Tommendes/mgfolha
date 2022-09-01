<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSfuncional */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<div class="cad-sfuncional-form">

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
            <?= $form->field($model, 'id_local_trabalho')->textInput(['placeholder' => $model->getAttributeLabel('id_local_trabalho')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_cad_principal')->textInput(['placeholder' => $model->getAttributeLabel('id_cad_principal')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_escolaridade')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('id_escolaridade')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'escolaridaderais')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('escolaridaderais')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'rais')->textInput(['placeholder' => $model->getAttributeLabel('rais')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'dirf')->textInput(['placeholder' => $model->getAttributeLabel('dirf')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'sefip')->textInput(['placeholder' => $model->getAttributeLabel('sefip')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'sicap')->textInput(['placeholder' => $model->getAttributeLabel('sicap')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'insalubridade')->textInput(['placeholder' => $model->getAttributeLabel('insalubridade')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'decimo')->textInput(['placeholder' => $model->getAttributeLabel('decimo')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_vinculo')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('id_vinculo')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'id_cat_sefip')->textInput(['placeholder' => $model->getAttributeLabel('id_cat_sefip')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'ocorrencia')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('ocorrencia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'carga_horaria')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('carga_horaria')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'molestia')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('molestia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_laudomolestia')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_laudomolestia')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'manad_tiponomeacao')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('manad_tiponomeacao')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'manad_numeronomeacao')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('manad_numeronomeacao')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_tempo')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_tempo')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_tempofim')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_tempofim')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_beneficio')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_beneficio')]) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'n_valorbaseinss')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('n_valorbaseinss')]) ?>
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
