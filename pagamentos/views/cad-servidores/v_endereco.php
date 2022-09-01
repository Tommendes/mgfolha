<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadServidores;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadServidores */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadServidoresController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$photo = strtolower(Yii::$app->user->identity->cliente . '/' . Yii::$app->user->identity->dominio . '/imagens/servidores/' . $model->url_foto);
$photo_root = Yii::getAlias('@uploads_root') . '/' . $photo;
$photo_url = Yii::getAlias('@uploads_url') . '/' . $photo;
$label = (!file_exists($photo_root)) ? 'Enviar foto' : 'Alterar foto';
$avatar = Yii::getAlias('@imgs_url') . DIRECTORY_SEPARATOR . 'default_avatar_' . ($model->sexo === 1 ? 'fe' : '') . 'male.jpg';
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadServidores::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = Yii::$app->user->identity->cadastros >= 3;
?>
<div class="cad-servidores-view">
    <div class="row">
        <div class="col-md-12">
            <?php
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'cep',
                        'value' => $model->cep,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'logradouro',
                        'value' => $model->logradouro,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'numero',
                        'format' => ['integer'],
                        'value' => $model->numero,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'complemento',
                        'value' => $model->complemento,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'bairro',
                        'value' => $model->bairro,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'cidade',
                        'value' => $model->cidade,
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'uf',
                        'value' => common\models\Listas::getUf($model->uf),
                        'type' => DetailView::INPUT_SELECT2,
                        'widgetOptions' => [
                            'data' => common\models\Listas::getUfs(),
                            'options' => ['placeholder' => 'Selecione ...'],
                            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                        ],
                        'options' => ['readonly' => !$buttons],
                        'displayOnly' => !$buttons,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'evento',
                        'value' => Yii::$app->user->identity->gestor ?
                        Html::button($evento, [
                            'value' => Url::to(['/sis-events/' . $model->evento]),
                            'title' => Yii::t('yii', 'Ver evento'),
                            'class' => 'showModalButton button-as-link'
                        ]) : $evento,
                        'format' => 'raw',
                        'label' => 'Último evento',
                        'displayOnly' => true,
                    ],
                ]
            ];
            $attributes[] = [
                'columns' => [
                    [
                        'attribute' => 'created_at',
                        'value' => date('d-m-Y H:i:s', $model->created_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => date('d-m-Y H:i:s', $model->updated_at),
                        'options' => ['readonly' => true],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ]
            ];
            echo DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || CadServidores::STATUS_CANCELADO === $model->status || !$sf || $model->getFalecimento() !== null ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => '', //(isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons && $sf && $model->getFalecimento() == null ? '{update} {delete}' : $label_statusCancelado),
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => CadServidoresController::CLASS_VERB_NAME . ': ' . str_pad($model->matricula, 8, '0', STR_PAD_LEFT),
                    'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
                ],
                'attributes' => $attributes,
            ])
            ?>

        </div>
    </div>

</div>
<?php
$js = <<<JS
    $(document).ready(function () {

        //Quando o campo cep perde o foco.
        $("#cadservidores-cep").blur(function () {                  

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#cadservidores-logradouro").val("");
                $("#cadservidores-bairro").val("");
                $("#cadservidores-cidade").val("");
                $("#cadservidores-cidade").removeAttr("disabled");
                $("#cadservidores-uf").val("");
                $("#cadservidores-uf").removeAttr("disabled");
            } 
        
            krajeeDialog.confirm("Deseja atualizar os dados do endereço a partir do CEP informado?", function (result) {
                if (result) {     
        
                    //Nova variável "cep" somente com dígitos.
                    var cep = $("#cadservidores-cep").val().replace(/\D/g, '');

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
        });
    });
JS;
$this->registerJs($js);
