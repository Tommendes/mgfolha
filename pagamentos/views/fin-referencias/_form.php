<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinReferencias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fin-referencias-form">

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
        $model->data = date('1/%m/%Y');
        $model->valor = number_format(0, 2, '.', '');
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
        <div class="col-md-9">
            <?=
            $form->field($model, 'id_pccs')->dropDownList(ArrayHelper::map(pagamentos\models\CadPccs::find()
                                    ->select([
                                        'id' => 'cad_pccs.id_pccs',
                                        'nome_pccs' => 'cad_pccs.nome_pccs',
                                    ])
                                    ->join('join', 'fin_referencias', 'fin_referencias.id_pccs = cad_pccs.id_pccs')
                                    ->orderBy('cad_pccs.nome_pccs')->asArray()->all(), 'id', 'nome_pccs')
                    , ['prompt' => 'Selecione...'])
            ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'id_classe')->dropDownList(ArrayHelper::map(pagamentos\models\CadClasses::find()
                                    ->select([
                                        'id' => 'cad_classes.id_classe',
                                        'nome_classe' => 'cad_classes.nome_classe',
                                    ])
                                    ->join('join', 'fin_referencias', 'fin_referencias.id_classe = cad_classes.id_classe')
                                    ->groupBy('cad_classes.nome_classe')
                                    ->orderBy('cad_classes.nome_classe')->asArray()->all(), 'id', 'nome_classe')
                    , ['prompt' => 'Selecione...'])
            ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'referencia')->widget(kartik\touchspin\TouchSpin::classname(), [
                'pluginOptions' => [
                    'verticalbuttons' => false,
                    'verticalup' => '<i class="fas fa-plus"></i>',
                    'buttonup_class' => 'btn btn-outline-secondary bootstrap-touchspin-down ml-0',
                    'verticaldown' => '<i class="fas fa-minus"></i>',
                    'buttondown_class' => 'btn btn-outline-secondary bootstrap-touchspin-down mr-0',
                ],
                'options' => [
                    'placeholder' => $model->getAttributeLabel('referencia'),
                    'class' => 'form-control mr-0 ml-0',
                    'placeholder' => 'Referência...',
                ],
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'valor')->textInput(['placeholder' => $model->getAttributeLabel('valor')]) ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'data')->widget(MaskedInput::classname(), [
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
$js = <<<JS
    $('#finreferencias-valor').on('input', function () {
        var txt = $(this).val();
        $(this).val(txt.replace(",", "."));
    });    
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
