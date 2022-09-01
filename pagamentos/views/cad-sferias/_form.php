<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSferias */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<div class="cad-sferias-form">

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

        <div class="col-md-3">
            <?=
            $form->field($model, 'd_ferias')->widget(MaskedInput::classname(), [
                'name' => 'nascimento_d',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_nascimento_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'periodo')->textInput(['placeholder' => $model->getAttributeLabel('periodo')]) ?>
        </div>

        <div class="col-md-12">
            <?=
            $form->field($model, 'observacoes')->widget(TinyMce::className(), [
                'language' => 'pt_BR',
                'clientOptions' => [
//                    'plugins' => [
//                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
//                        'searchreplace wordcount visualblocks visualchars code fullscreen',
//                        'insertdatetime media nonbreaking save table contextmenu directionality',
//                        'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc help'
//                    ],
//                    'toolbar1' => "undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor emoticons | codesample helpimage",
                    'toolbar2' => ""
                ],
                'options' => [
                    'rows' => 3,
                    'id' => Yii::$app->security->generateRandomString(),
                ],
            ])
            ?>
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
