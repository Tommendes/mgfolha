<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSdependentes */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
?>

<div class="cad-sdependentes-form">

    <?php
    $submit_cadas_id = Yii::$app->security->generateRandomString(8);
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
    <?= $form->field($model, 'matricula')->hiddenInput()->label(false) ?>

    <div class="row"> 

        <div class="col-md-2">
            <?=
            $form->field($model, 'cpf')->widget(MaskedInput::classname(), [
                'mask' => '999.999.999-99',
                'options' => [
                    'alias' => 'cpf',
                    'class' => 'form-control',
                    'placeholder' => '###.###.###-##',
                    'autofocus' => $model->isNewRecord,
                    'id' => $id_cpf = Yii::$app->security->generateRandomString(8),
                ],
            ]);
            ?>
        </div>

        <div class="col-md-10">
            <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('nome')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'rg')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('rg')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'rg_emissor')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('rg_emissor')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'rg_uf')->dropDownList(common\models\Listas::getUfs(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'rg_d')->widget(MaskedInput::classname(), [
                'name' => 'rg_d',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_rg_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'tipo')->dropDownList(common\models\Listas::getTiposDependencia(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'permanente')->dropDownList(common\models\Listas::getSNs(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'nascimento_d')->widget(MaskedInput::classname(), [
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

        <div class="col-md-2">
            <?=
            $form->field($model, 'inss_prz')->widget(MaskedInput::classname(), [
                'name' => 'inss_prz',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'title' => 'Este prazo será igual à data de nascimento mais 14 anos',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_inss_prz = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'rpps_prz')->widget(MaskedInput::classname(), [
                'name' => 'rpps_prz',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'title' => 'Este prazo será igual à data de nascimento mais 14 anos',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_rpps_prz = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'irrf_prz')->widget(MaskedInput::classname(), [
                'name' => 'irrf_prz',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'title' => 'Este prazo será igual à data de nascimento mais 21 anos',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_irrf_prz = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'certidao')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('certidao')]) ?>
        </div>

        <div class="col-md-1">
            <?= $form->field($model, 'livro')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('livro')]) ?>
        </div>

        <div class="col-md-1">
            <?= $form->field($model, 'folha')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('folha')]) ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'certidao_d')->widget(MaskedInput::classname(), [
                'name' => 'rpps_prz',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_certidao_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'carteira_vacinacao')->dropDownList(common\models\Listas::getSNs(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'historico_escolar')->dropDownList(common\models\Listas::getSNs(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'plano_saude')->dropDownList(common\models\Listas::getSNs(), ['prompt' => 'Selecione...']) ?>
        </div>
        <?php
        if (1 == 2):
//            há dúvidas quanto ao objetivo deste campo
            ?>
            <div class="col-md-3">
                <?= $form->field($model, 'relacao_dependecia')->dropDownList(common\models\Listas::getTiposDependencia(), ['prompt' => 'Selecione...']) ?>
            </div>
        <?php endif; ?>

        <div class="col-md-3">
            <?= $form->field($model, 'pensionista')->dropDownList(common\models\Listas::getSNs(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'irpf')->dropDownList(
                    common\models\Listas::getSNs(), [
                'prompt' => 'Selecione...',
                'title' => 'Este dependente deve ser incluído na declaração do servidor?',
                'data-toggle' => 'tooltip',
                'data-placement' => 'right',
                'onchange' => new \yii\web\JsExpression("if($('#cadsdependentes-irpf').val() == 1 && $('#$id_cpf').val().replace( /[^\d.]/g, '' ).length < 11){"
                        . "PNotify.removeAll();"
                        . "new PNotify({"
                        . "title: 'Aviso', "
                        . "text: 'Obrigatório informar o CPF do dependente',"
                        . "type: 'warning',"
                        . "styling: 'fontawesome',"
                        . "nonblock: {nonblock: false},"
                        . "});"
                        . "$('#" . $submit_cadas_id . "').attr( 'disabled', 'disabled' );"
                        . "$('#" . $id_cpf . "').addClass( 'required' );"
                        . "$('#" . $id_cpf . "').focus();"
                        . "} else {"
                        . "$('#" . $id_cpf . "').removeClass( 'required' );"
                        . "}")
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?= Html::label('', null, []) ?>
            <div class="form-group" style="padding-top: 9px;">
                <div class="btn-group d-flex" role="group">
                    <?=
                    (!$sf ? '' : Html::submitButton(Yii::t('yii', 'Save'), ['id' => $submit_cadas_id, 'class' => 'btn btn-success w-' . (isset($modal) && $modal ? '50' : '100')]))
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
$url_cpf = Url::to(['cad-sdependentes/r?q=']);
$url_id_cad_servidores = 'id_cad_servidores=' . $model->id_cad_servidores;
$url_id = '&i=' . $model->id;
$url_dpl = Url::to(['cad-sdependentes/dpl/']);
$servidor_responsavel = $model->getCadServidor($model->id_cad_servidores);
$servidor_responsavel = $servidor_responsavel->nome . ', <br>CPF ' . $servidor_responsavel->cpf . ' e matricula ' . $servidor_responsavel->matricula;
$js = <<< JS
    $('#$id_cpf').focusout(function () {        
        var str = $(this).val();
        var url_get = '$url_cpf' + str + '&$url_id_cad_servidores'+'$url_id';
        var url_dpl = '$url_dpl/';
        var tamanho = $(this).val().length;
        if (tamanho >= 11){
            var resultado = 0;
            var cadas_res = '<ol>', sl, rs, nm;
            $.ajax({
                url: url_get,
                success: function (json) {
                    var obj = jQuery.parseJSON( json );
                    if (obj.id > 0) {
                        var pos = 0;
                        $.each(obj.result, function() {
                            $.each(this, function(i, data) {
                                sl = data;
                                if (pos == 0){
                                    url_dpl += data;
                                    pos++;
                                }
                            });
                            cadas_res = cadas_res + '<li>' + sl + '</li>';
                        });        
                        krajeeDialog.confirm("<p>Este CPF já pertence a outro dependente conforme abaixo.</p>" 
                            + cadas_res + "</ol>"
                            + "Deseja duplicar os dados do cadastro existente tendo como servidor responsável o servidor atual: $servidor_responsavel?", function (result) {
                            if (result) { // ok button was pressed      
                                $.ajax({
                                    url: url_dpl + '?$url_id_cad_servidores',
                                    type: 'post',
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
                                    },
                                });          
                            } else { // confirmation was cancelled
                            }
                        });
                    } else {                        
                        $("#$submit_cadas_id").removeAttr( "disabled" );
                    }
                }
            });            
        }
    });
    $("#$id_nascimento_d").blur(function(){
        var val_nascimento_d = $(this).val();
        if (val_nascimento_d.length == 10){
            $('#$id_inss_prz').val(SomarData(val_nascimento_d, 0, 14, false));
            $('#$id_inss_prz').css({'background-color':'antiquewhite'});
            $('#$id_rpps_prz').val(SomarData(val_nascimento_d, 0, 14, false));
            $('#$id_rpps_prz').css({'background-color':'antiquewhite'});
            $('#$id_irrf_prz').val(SomarData(val_nascimento_d, 0, 21, false));
            $('#$id_irrf_prz').css({'background-color':'antiquewhite'});
        }
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
    