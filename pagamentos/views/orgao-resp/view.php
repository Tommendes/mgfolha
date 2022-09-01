<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\OrgaoRespController;
use common\models\OrgaoResp;
use pagamentos\controllers\AppController;
use kartik\icons\FontAwesomeAsset;
use yii\widgets\MaskedInput;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoResp */

$this->title = OrgaoRespController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => OrgaoRespController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);
?>
<div class="orgao-resp-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\OrgaoResp::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'nome_gestor',
                'value' => $model->nome_gestor,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:50%;vertical-align: middle;']
            ],
            [
                'attribute' => 'uf',
                'value' => $model->uf,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:10%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cpf_gestor',
                'value' => AppController::setCpfCnpjMask($model->cpf_gestor),
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'd_nascimento',
                'value' => date('d-M-Y', strtotime($model->d_nascimento)),
//                'format'=>'d-M-Y',
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !Yii::$app->user->identity->gestor,
                ],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cep',
                'value' => $model->cep,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'logradouro',
                'value' => $model->logradouro,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'numero',
                'value' => $model->numero,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'complemento',
                'value' => $model->complemento,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'bairro',
                'value' => $model->bairro,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'cidade',
                'value' => $model->cidade,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'email',
                'value' => $model->email,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'telefone',
                'value' => $model->telefone,
                'options' => ['readonly' => !Yii::$app->user->identity->gestor],
                'displayOnly' => !Yii::$app->user->identity->gestor,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'group' => true,
        'label' => $model->getAttributeLabel('evento') . ' ' . (
        Yii::$app->user->identity->gestor ?
        Html::button($evento, [
            'value' => Url::to(['/sis-events/' . $model->evento]),
            'title' => Yii::t('yii', 'Ver evento'),
            'class' => 'showModalButton button-as-link'
        ]) : $evento) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
        . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
        'groupOptions' => ['class' => 'table-info text-center']
    ];
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || OrgaoResp::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => OrgaoRespController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
<?php
$js = <<<JS
    $(document).ready(function () {

        //Quando o campo cep perde o foco.
        $("#orgaoresp-cep").blur(function () {                  

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#orgaoresp-logradouro").val("");
                $("#orgaoresp-bairro").val("");
                $("#orgaoresp-cidade").val("");
                $("#orgaoresp-cidade").removeAttr("disabled");
                $("#orgaoresp-uf").val("");
                $("#orgaoresp-uf").removeAttr("disabled");
            } 
        
            krajeeDialog.confirm("Deseja atualizar os dados do endereço a partir do CEP informado?", function (result) {
                if (result) {     
        
                    //Nova variável "cep" somente com dígitos.
                    var cep = $("#orgaoresp-cep").val().replace(/\D/g, '');

                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if (validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("#orgaoresp-logradouro").val("...");
                            $("#orgaoresp-bairro").val("...");
                            $("#orgaoresp-cidade").val("...");
                            $("#orgaoresp-uf").val("...");

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("#orgaoresp-logradouro").val(dados.logradouro);
                                    $("#orgaoresp-logradouro").css('background-color','antiquewhite');
                                    $("#orgaoresp-bairro").val(dados.bairro);
                                    $("#orgaoresp-bairro").css('background-color','antiquewhite');
                                    $("#orgaoresp-cidade").val(dados.localidade);
                                    $("#orgaoresp-cidade").css('background-color','antiquewhite');
                                    $('#orgaoresp-uf').val(dados.uf);
                                    $("#orgaoresp-uf").css('background-color','antiquewhite');
                                    $("#orgaoresp-numero").focus(); 
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
?>
