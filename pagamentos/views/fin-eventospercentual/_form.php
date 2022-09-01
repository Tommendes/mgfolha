<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventospercentual */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-eventospercentual-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/'
                . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' : $model->slug) . ('?modal=' . $modal . '&cp=' . $model->id_fin_eventos),
                'id' => $idForm,
                'formConfig' => ['showErrors' => true]
    ]);
    if ($model->isNewRecord):
        $model->dominio = Yii::$app->user->identity->dominio;
        $model->slug = Yii::$app->security->generateRandomString();
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
        $model->data = date('d/m/Y');
    endif;
    ?>
    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'id_fin_eventos')->hiddenInput()->label(false) ?>
    <?php
    $id_eventos_percentual = pagamentos\models\FinEventospercentual::find()
            ->where([
                'dominio' => Yii::$app->user->identity->dominio,
                'id_fin_eventos' => $model->id_fin_eventos,
            ])
            ->max('id_eventos_percentual');
    $model->id_eventos_percentual = str_pad(
            (!$id_eventos_percentual == null ? $id_eventos_percentual + 1 : 1), 4, '0', STR_PAD_LEFT);
    ?>

    <div class="row"> 
        <div class="col-md-3">
            <?php // echo $form->field($model, 'id_eventos_percentual')->textInput(['maxlength' => 4, 'placeholder' => $model->getAttributeLabel('id_eventos_percentual'), 'read']) ?>
            <?= Html::tag('h2', $model->getAttributeLabel('id_eventos_percentual') . ': ' . $model->id_eventos_percentual) ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'data')->widget(MaskedInput::classname(), [
                'name' => 'perc_d',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_rg_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
//                    ->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('data')]) 
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($model, 'percentual')->widget(MaskMoney::classname(), [
                'pluginOptions' => [
                    'prefix' => 'R$ ',
                    'thousands' => '',
                    'decimal' => '.',
                    'precision' => 2
                ]
            ]); //->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('percentual')])
            ?>
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
