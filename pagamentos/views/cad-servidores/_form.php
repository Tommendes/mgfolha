<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use pagamentos\controllers\CadCidadesController;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadServidores */
/* @var $form yii\widgets\ActiveForm */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$photo = strtolower(Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/imagens/servidores/' . $model->id . '.png');
$photo_root = Yii::getAlias('@uploads_root') . '/' . $photo;
$photo_url = Yii::getAlias('@uploads_url') . '/' . $photo;
$label = (!file_exists($photo_root)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_' . ($model->sexo === 1 ? 'fe' : '') . 'male.jpg';
?>

<div class="cad-servidores-form">

    <?php
    $modal = !isset($modal) ? false : $modal;
    $idForm = Yii::$app->security->generateRandomString(8) . '-form';
    $containerPJax = $idForm . '-pjax';
    Pjax::begin(['id' => $containerPJax]);
    $form = ActiveForm::begin([
                'action' => Url::base(true) . '/../' . basename(getcwd()) . '/' . Yii::$app->controller->id . '/tom/' . ($model->isNewRecord ? 'c' : 'u')
                . '/' . ($model->isNewRecord ? '' :
                ($model->slug)) . ('?modal=' . $modal),
                'id' => $idForm,
//                'type' => ActiveForm::TYPE_INLINE,
//                'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
//                'fieldConfig' => ['options' => ['class' => 'form-group mr-2']], // spacing field groups
//                'formConfig' => ['showErrors' => true],
//                'options' => ['style' => 'align-items: flex-start'] // set style for proper tooltips error display
    ]);
    if ($model->isNewRecord):
        $model->dominio = Yii::$app->user->identity->dominio;
        $model->slug = strtolower(sha1($model->tableName() . time()));
        $model->status = $model::STATUS_ATIVO;
        $model->evento = 0;
        $model->created_at = $model->updated_at = time();
        $model->matricula = pagamentos\controllers\CadServidoresController::getMatricula() + time();
        $model->nacionalidade = $model::NACIONALIDADE_BR;
    endif;
    ?>
    <?= $form->field($model, 'dominio')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'slug')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'evento')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'matricula')->hiddenInput()->label(false) ?>

    <div class="row"> 
        <?php if (1 == 2): ?>
            <div class="col-md-3">
                <fieldset>
                    <legend>Foto do servidor (botão direito para opções):</legend>
                    <?=
                    Html::img(Url::home(true) . (!file_exists($photo_root) ? $avatar : $photo_url), ['style' => 'display: block; width: 100%; border-radius: 8px; padding-top: 5px;']) .
                    Html::button($label, [
                        'value' => Url::to(['photo', 'id' => $model->id, 'modal' => 1]),
                        'title' => Yii::t('yii', 'Uploading file'),
                        'class' => 'showModalButton btn btn-warning',
                        'style' => 'display: block; width: 100%;'
                    ])
                    ?> 
                </fieldset>
            </div>
        <?php endif; ?>

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
            <?=
            $form->field($model, 'nome')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('nome'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'rg')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('rg'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'rg_emissor')->textInput([
                'maxlength' => 4,
                'placeholder' => $model->getAttributeLabel('rg_emissor'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'rg_uf')->dropDownList(common\models\Listas::getUfs()) ?>
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
            <?=
            $form->field($model, 'pispasep')->widget(MaskedInput::classname(), [
                'name' => 'rg_d',
                'mask' => '999.99999.99.9',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('pispasep'),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'pispasep_d')->widget(MaskedInput::classname(), [
                'name' => 'pispasep_d',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_pispasep_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('titulo')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'titulosecao')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('titulosecao')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'titulozona')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('titulozona')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'ctps')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('ctps')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'ctps_serie')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('ctps_serie')]) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'ctps_uf')->dropDownList(common\models\Listas::getUfs()) ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'ctps_d')->widget(MaskedInput::classname(), [
                'name' => 'ctps_d',
                'clientOptions' => [
                    'alias' => 'date',
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'dd/mm/aaaa',
                    'id' => $id_ctps_d = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
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

        <div class="col-md-4">
            <?=
            $form->field($model, 'pai')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome do ' . $model->getAttributeLabel('pai'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'mae')->textInput([
                'maxlength' => true,
                'placeholder' => 'Nome da ' . $model->getAttributeLabel('mae'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'cep', [
                'addon' => ['type' => 'append', 'content' => '.00']
            ])->widget(MaskedInput::classname(), [
                'name' => 'cep',
                'mask' => ['99.999-999'],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => '##.###-###',
                    'id' => $id_cep = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-5">
            <?=
            $form->field($model, 'logradouro')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('logradouro'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-1">
            <?=
            $form->field($model, 'numero')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('numero')
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'complemento')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('complemento'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'bairro')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('bairro'),
                'style' => 'text-transform:uppercase',
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?php
            $id_mun = 'id_mun_' . Yii::$app->security->generateRandomString(8);
            $url_cidades = Yii::$app->urlManager->createUrl('/cad-cidades/lists/');
            echo $form->field($model, 'uf')->dropDownList(common\models\Listas::getUfs(), [
                'prompt' => 'Selecione...',
                'onchange' => " 
                    var url = '$url_cidades/'+$(this).val();
                    $.post( url, function( data ) {
                      $('select#$id_mun').html( data );
                    });
                ",
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?php
            $cad_cidades = ArrayHelper::map(CadCidadesController::getCadList($model->uf), 'municipio_nome', 'municipio_nome', ['prompt' => 'Selecione...']);
            echo $form->field($model, 'cidade')
                    ->dropDownList($cad_cidades, [
                        'id' => $id_mun,
                        'prompt' => 'Selecione uma opção...',
            ]);
            ?>
        </div>

        <div class="col-md-2">
            <?php
            $id_naturalidade = 'id_nat_' . Yii::$app->security->generateRandomString(8);
            echo $form->field($model, 'naturalidade_uf')->dropDownList(common\models\Listas::getUfs(), [
                'prompt' => 'Selecione...',
                'onchange' => " 
                    var url = '$url_cidades/'+$(this).val();
                    $.post( url, function( data ) {
                      $('select#$id_naturalidade').html( data );
                    });
                ",
            ])
            ?>
        </div>

        <div class="col-md-3">
            <?php
            echo $form->field($model, 'naturalidade')
                    ->dropDownList($cad_cidades, [
                        'id' => $id_naturalidade,
                        'prompt' => 'Selecione uma opção...',
            ]);
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'telefone')->widget(MaskedInput::classname(), [
                'name' => 'telefone',
                'mask' => ['(99)999-99999', '(99)99999-9999'],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('telefone'),
                    'id' => $id_telefone = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'celular')->widget(MaskedInput::classname(), [
                'name' => 'celular',
                'mask' => ['(99)999-99999', '(99)99999-9999'],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => $model->getAttributeLabel('celular'),
                    'id' => $id_celular = Yii::$app->security->generateRandomString(8),
                ],
            ])
            ?>
        </div>

        <div class="col-md-4">
            <?=
            $form->field($model, 'email')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('email'),
                'style' => 'text-transform:lowercase',
            ])
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'idbanco')->dropDownList(common\models\Bancos::getBancos(), ['prompt' => 'Selecione...']) ?>
        </div>

        <div class="col-md-1">
            <?=
            $form->field($model, 'banco_agencia')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('banco_agencia')
            ])
            ?>
        </div>

        <div class="col-md-1">
            <?=
            $form->field($model, 'banco_agencia_digito')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('###')
            ])->label('Dígito')
            ?>
        </div>

        <div class="col-md-2">
            <?=
            $form->field($model, 'banco_conta')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('banco_conta')
            ])
            ?>
        </div>

        <div class="col-md-1">
            <?=
            $form->field($model, 'banco_conta_digito')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('###')
            ])->label('Dígito')
            ?>
        </div>

        <div class="col-md-1">
            <?=
            $form->field($model, 'banco_operacao')->textInput([
                'maxlength' => true,
                'placeholder' => $model->getAttributeLabel('###')
            ])->label('Operação')
            ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'nacionalidade')->dropDownList(common\models\Listas::getNacionalidades()) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'sexo')->dropDownList(common\models\Listas::getSexos()) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'raca')->dropDownList(common\models\Listas::getRacas()) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'estado_civil')->dropDownList(common\models\Listas::getEstados_civis()) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'tipodeficiencia')->dropDownList(common\models\Listas::getTiposdeficiencia()) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'd_admissao')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('d_admissao')]) ?>
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
if ($model->isNewRecord) {
    $url_cpf = Url::to(['cad-servidores/r?q=']);
    $url_id = '&i=' . $model->id;
    $js = <<< JS
    $('#$id_cpf').focusout(function () {        
        var str = $(this).val();
        var tamanho = $(this).val().length;
        if (tamanho >= 11){
            var resultado = 0;
            var cadas_res = '<ol>', sl, rs, nm;
            $.ajax({
                url: '$url_cpf' + str + '$url_id',
                success: function (json) {
                    var obj = jQuery.parseJSON( json );
                    if (obj.id > 0) {
//                        $('#$submit_cadas_id').addClass('disabled');
                        $("#$submit_cadas_id").attr( "disabled", "disabled" );
                        $.each(obj.result, function() {
                            $.each(this, function(i, data) {
                                sl = data; 
                            });
                            cadas_res = cadas_res + '<li>' + sl + '</li>';
                        });        
                        krajeeDialog.alert("<p>Já há uma ou mais matrículas com este CPF. <strong>Veja abaixo.</strong></p>"
                            + "<p>Selecione um deles na lista abaixo para editar ou clique no botão "
                            + "ao lado para duplicar.</p>" 
                            + cadas_res + "</ol>");         
                    } else {                        
                        $("#$submit_cadas_id").removeAttr( "disabled" );
                    }
                }
            });            
        }
    });
JS;
    $this->registerJs($js);
}
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

$js = <<<JS
    $(document).ready(function () {

        //Quando o campo cep perde o foco.
        $("#$id_cep").blur(function () {                  
            if ($("#$id_cep").val().length == 10) {
                function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("#cadservidores-logradouro").val("");
                    $("#cadservidores-bairro").val("");
                    $("#cadservidores-cidade").val("");
                    $("#cadservidores-cidade").removeAttr("disabled");
                    $("#cadservidores-uf").val("");
                    $("#cadservidores-uf").removeAttr("disabled");
                } 

                krajeeDialog.confirm("Gostaria de atualizar os dados do endereço a partir do CEP informado?", function (result) {
                    if (result) {     

                        //Nova variável "cep" somente com dígitos.
                        var cep = $("#$id_cep").val().replace(/\D/g, '');

                        //Verifica se campo cep possui valor informado.
                        if (cep != "") {

                            //Expressão regular para validar o CEP.
                            var validacep = /^[0-9]{8}$/;

                            //Valida o formato do CEP.
                            if (validacep.test(cep)) {

                                //Preenche os campos com "..." enquanto consulta webservice.
                                $("#cadservidores-logradouro").val("...");
                                $("#cadservidores-bairro").val("...");
                                $("#cadservidores-cidade").val("...");
                                $("#cadservidores-uf").val("...");

                                //Consulta o webservice viacep.com.br/
                                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                                    if (!("erro" in dados)) {
                                        //Atualiza os campos com os valores da consulta.
                                        $("#cadservidores-logradouro").val(dados.logradouro);
                                        $("#cadservidores-logradouro").css('background-color','antiquewhite');
                                        $("#cadservidores-bairro").val(dados.bairro);
                                        $("#cadservidores-bairro").css('background-color','antiquewhite');
                                        $("#cadservidores-cidade").val(dados.localidade);
                                        $("#cadservidores-cidade").css('background-color','antiquewhite');
                                        $('#cadservidores-uf').val(dados.uf);
                                        $("#cadservidores-uf").css('background-color','antiquewhite');
                                        $("#cadservidores-numero").focus(); 
                                    } //end if.
                                    else {
                                        //CEP pesquisado não foi encontrado.
                                        limpa_formulário_cep();
                                        krajeeDialog.alert("CEP não encontrado.");
                                    }
                                });
                            } //end if.
                            else {
                                //cep é inválido.
                                limpa_formulário_cep();
                                krajeeDialog.alert("Formato de CEP inválido.");
                            }
                        } //end if.
                        else {
                            //cep sem valor, limpa formulário.
                            limpa_formulário_cep();
                        }
                    } else {
                        return;
                    }
                });
            }
        });
    });
JS;
$this->registerJs($js);

