<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoResp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orgao-resp-form">

    <?php
    $cp = Yii::$app->request->queryParams['cp'];
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id
                . '/' . ($model->isNewRecord ? '' :
                ($model->id)) . ('?modal=' . $modal),
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
    <?php
    $idcepinput = Yii::$app->security->generateRandomString(8);
    $idloginput = Yii::$app->security->generateRandomString(8);
    $idbaiinput = Yii::$app->security->generateRandomString(8);
    $idcidinput = Yii::$app->security->generateRandomString(8);
    $idufinput = Yii::$app->security->generateRandomString(8);
    $idpaiinput = Yii::$app->security->generateRandomString(8);
    ?>

    <div class="row">

        <div class="col-md-3">
            <?= $form->field($model, 'cpf_gestor')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'nome_gestor')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'd_nascimento')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'aaaa/mm/dd'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'autoclose' => true,
                ]
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'cep', ['selectors' => ['input' => "#$idcepinput"]])->textInput([
                'maxlength' => 8,
                'id' => $idcepinput,
                'placeholder' => "Apenas números(########)",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'logradouro', ['selectors' => ['input' => "#$idloginput"]])->textInput([
                'maxlength' => 100,
                'id' => $idloginput,
                'placeholder' => "Nome da rua",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'numero')->textInput([
                'placeholder' => "Apenas números(###)",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'complemento')->textInput([
                'maxlength' => 50,
                'placeholder' => "Complemento do número",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'bairro', ['selectors' => ['input' => "#$idbaiinput"]])->textInput([
                'maxlength' => 50,
                'id' => $idbaiinput,
                'placeholder' => "Bairro",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'cidade', ['selectors' => ['input' => "#$idcidinput"]])->textInput([
                'maxlength' => 80,
                'id' => $idcidinput,
                'placeholder' => "Município do endereço",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?=
            $form->field($model, 'uf', ['selectors' => ['input' => "#$idufinput"]])->textInput([
                'maxlength' => 2,
                'id' => $idufinput,
                'placeholder' => "Estado",
            ])
            ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        </div>

        <div class="col-md-3">

            <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

        </div>


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

    </div>
    <?php
    ActiveForm::end();
    Pjax::end();
    ?>

</div>
<?php
$js = <<<JS
    $(document).ready(function () {
        
        function limpa_formulário_cep() {
            // Limpa valores do formulário de cep.
            $("#$idloginput").val("");
            $("#$idbaiinput").val("");
            $("#$idcidinput").val("");
            $("#$idcidinput").removeAttr("disabled");
            $("#$idufinput").val("");
            $("#$idufinput").removeAttr("disabled");
            $("#cadastrosenderecos-ibge").val("");
            $("#$idpaiinput").val("Brasil");
        } 

        //Quando o campo cep perde o foco.
        $("#$idcepinput").blur(function () {

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#$idloginput").val("...");
                    $("#$idbaiinput").val("...");
                    $("#$idcidinput").val("...");
                    $("#$idufinput").val("...");
                    $("#cadastrosenderecos-ibge").val("...");
                    $("#$idpaiinput").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#$idloginput").val(dados.logradouro);
                            $("#$idbaiinput").val(dados.bairro);
//                            $('#$idcidinput').replaceWith('<input type="text" id="empresa-cidade" class="form-control">');
                            $("#$idcidinput").val(dados.localidade);
//                            $('#$idufinput').replaceWith('<input type="text" id="empresa-uf" class="form-control">');
                            $('#$idufinput').val(dados.uf);
                            $("#cadastrosenderecos-ibge").val(dados.ibge);
                            $("#$idpaiinput").val("Brasil"); 
                            $("#$idpaiinput").attr("disabled", true);
                            $("#cadastrosenderecos-nr").focus(); 
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep();
                            krajeeDialog.alert("CEP não encontrado.");
                        }
                    });
                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    krajeeDialog.alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        });
    });
JS;
$this->registerJs($js);
?>
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
