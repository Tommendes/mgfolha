<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\OrgaoController;
use common\models\Orgao;
use common\models\OrgaoUaSearch;
use common\models\OrgaoRespSearch;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;
use common\models\Listas;
use pagamentos\controllers\CadCidadesController;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Orgao */

$this->title = OrgaoController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => OrgaoController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);
$dominios = ArrayHelper::map(Orgao::find()->select(['dominio' => 'dominio'])
                        ->orderBy('dominio')->groupBy('dominio')->all(), 'dominio', 'dominio');
$logoPath = Yii::getAlias('@uploads_root') . '/imagens/'
        . strtolower(Yii::$app->user->identity->cliente) . '/'
        . strtolower(Yii::$app->user->identity->dominio) . '/';
$logo = pathinfo($model->url_logo);
$logo = $logoPath . $logo['basename'];
$label = (!file_exists($logo) || is_null($model->url_logo)) ? 'Enviar logomarca' : 'Alterar logomarca';
?>

<?php
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\Orgao::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
$buttons = (Yii::$app->user->identity->base_servico === 'pagamentos') && ((!Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1) ||
        (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] > 0));

$attributes[] = [
    'columns' => [
        [
            'attribute' => 'cnpj',
            'value' => AppController::setCpfCnpjMask($model->cnpj),
            'options' => [
                'readonly' => !Yii::$app->user->identity->gestor,
                'id' => $cnpj_id = Yii::$app->security->generateRandomString(8),
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'dominio',
            'value' => $model->dominio,
            'format' => 'raw',
            'label' => 'Domínio',
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $dominios,
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->administrador,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ]
    ]
];
$attributes[] = [
    'attribute' => 'orgao',
    'value' => $model->orgao,
    'options' => ['readonly' => !Yii::$app->user->identity->gestor],
    'displayOnly' => !Yii::$app->user->identity->gestor,
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
            'format' => ['integer'],
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
$cad_cidades = ArrayHelper::map(CadCidadesController::getCadList($model->uf), 'municipio_nome', 'municipio_nome', ['prompt' => 'Selecione...']);
$id_mun = 'id_mun_' . Yii::$app->security->generateRandomString(8);
$url_cidades = Yii::$app->urlManager->createUrl('/cad-cidades/lists/');
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
            'attribute' => 'uf',
            'value' => common\models\Listas::getUf($model->uf),
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => common\models\Listas::getUfs(),
                'options' => [
                    'placeholder' => 'Selecione ...',
                ],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'options' => [
                'readonly' => !Yii::$app->user->identity->gestor,
                'maxlength' => 2,
                'onchange' => " 
                    var url = '$url_cidades/'+$(this).val();
                    $.post( url, function( data ) {
                      $('select#$id_mun').html( data );
                    });
                ",
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'cidade',
            'value' => $model->cidade,
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => $cad_cidades,
                'options' => [
                    'prompt' => 'Selecione uma opção...',
//                    'placeholder' => 'Selecione ...'
                ],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'options' => [
                'readonly' => !Yii::$app->user->identity->gestor,
                'id' => $id_mun,
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'mes_descsindical',
            'format' => 'raw',
            'value' => Listas::getMes($model->mes_descsindical),
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => Listas::getMeses(),
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'telefone',
            'value' => $model->telefone,
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'email',
            'value' => $model->email,
            'format' => ['email'],
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'codigo_fpas',
            'value' => Listas::getCodigoFpas($model->codigo_fpas),
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => Listas::getCodigosFpas(),
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
        [
            'attribute' => 'codigo_gps',
            'value' => Listas::getCodigoGps($model->codigo_gps),
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => Listas::getCodigosGps(),
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'columns' => [
        [
            'attribute' => 'codigo_cnae',
            'value' => Listas::getCodigoCnae($model->codigo_cnae),
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'type' => DetailView::INPUT_SELECT2,
            'widgetOptions' => [
                'data' => Listas::getCodigosCnae(),
                'options' => ['placeholder' => 'Selecione ...'],
                'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
            ],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:45%;vertical-align: middle;']
        ],
        [
            'attribute' => 'codigo_ibge',
            'value' => $model->codigo_ibge,
            'options' => ['readonly' => !Yii::$app->user->identity->gestor],
            'displayOnly' => !Yii::$app->user->identity->gestor,
            'valueColOptions' => ['style' => 'width:15%;vertical-align: middle;']
        ],
    ]
];
$attributes[] = [
    'attribute' => 'codigo_fgts',
    'value' => Listas::getCodigoFgts($model->codigo_fgts),
    'options' => ['readonly' => !Yii::$app->user->identity->gestor],
    'type' => DetailView::INPUT_SELECT2,
    'widgetOptions' => [
        'data' => Listas::getCodigosFgts(),
        'options' => ['placeholder' => 'Selecione ...'],
        'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
    ],
    'displayOnly' => !Yii::$app->user->identity->gestor,
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
<div class="orgao-view">
    <div class="row">
        <div class="col-md-6" style="padding-top: 5px;">
            <?=
            DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !isset(Yii::$app->request->queryParams['mv']) || Orgao::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => $buttons ? '{update} {delete}' : '',
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => OrgaoController::CLASS_VERB_NAME,
                    'type' => DetailView::TYPE_INFO,
                ],
                'formOptions' => ['options' => ['enctype' => 'multipart/form-data']],
                'attributes' => $attributes,
            ])
            ?>
        </div>
        <div class="col-md-4" style="padding-top: 5px;">
            <ul class="list-group" style="padding-bottom: 5px;">
                <li class="list-group-item active">Responsável DIRF:</li>
                <?php
                $searchModel = new OrgaoRespSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);

                echo $this->render('@pagamentos/views/orgao-resp/list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'cp' => $model->id,
                    'buttons' => $buttons,
                ]);
                ?>
            </ul>
            <ul class="list-group" style="padding-bottom: 5px;">
                <li class="list-group-item active">Unidades autônomas:</li>
                <?php
                $searchModel = new OrgaoUaSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model->id);

                echo $this->render('@pagamentos/views/orgao-ua/list', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'cp' => $model->id,
                    'buttons' => $buttons,
                ]);
                ?>
            </ul>
        </div>
        <div class="col-md-2" style="padding-top: 5px;">
            <ul class="list-group" style="padding-bottom: 5px;">
                <li class="list-group-item active">Logomarca do orgão:</li>
                    <?=
                    (file_exists($logo) ?
                            Html::img($model->url_logo, ['style' => 'background-color: #f5f0f0e0; display: block; width: 100%; border-radius: 5px;']) : 'Logo: ' . $logo) .
                    ($buttons ?
                            Html::button($label, [
                                'value' => Url::to(['upload', 'id' => $model->id, 'modal' => 1]),
                                'title' => Yii::t('yii', 'Uploading file'),
                                'class' => 'showModalButton btn btn-warning w-100 modal-default',
//                                'style' => 'display: block; width: 100%;'
                            ]) : '')
                    ?>
            </ul>
        </div>
    </div>
</div>
<?php
$usuario = Yii::$app->user->identity->username;
$tabela = $model->tableName();
$url_ev = Url::home(true) . 'sis-events/c?evento=Consulta a cnpj ';
$js = <<< JS
        $('#$cnpj_id').focusout(function () {
            var str = $(this).val();
            var tamanho = str.length;
            if (tamanho === 14) {
                $.ajax({
                    url: 'https://www.receitaws.com.br/v1/cnpj/' + str,
                    dataType: 'jsonp',
                    success: function (json) {
                        if (json.status === 'ERROR') {
                            krajeeDialog.alert(json.message);
                        } else {
                            krajeeDialog.confirm("Gostaria de atualizar os dados de acordo com a RFB?", function (result) {
                                if (result) {
                                    $('#orgao-telefone').val(json.telefone);
                                    $('#orgao-telefone').css({'background-color': 'antiquewhite'});
                                    $('#orgao-orgao').val(json.nome);
                                    $('#orgao-orgao').css({'background-color': 'antiquewhite'});
                                    $('#orgao-email').val(json.email);
                                    $('#orgao-email').css({'background-color': 'antiquewhite'});
                                    $('#orgao-logradouro').val(json.logradouro);
                                    $('#orgao-logradouro').css({'background-color': 'antiquewhite'});
                                    $('#orgao-bairro').val(json.bairro);
                                    $('#orgao-bairro').css({'background-color': 'antiquewhite'});
                                    $('#orgao-numero').val(json.numero);
                                    $('#orgao-numero').css({'background-color': 'antiquewhite'});
                                    $('#orgao-cep').val(json.cep.replace('.','').replace('-',''));
                                    $('#orgao-cep').css({'background-color': 'antiquewhite'});
                                    $('#orgao-cidade').val(json.municipio);
                                    $('#orgao-cidade').css({'background-color': 'antiquewhite'});
                                }
                            });
                            $.get('$url_ev' + str + '&classevento=wsConsCnpj&username=$usuario&tabela_bd=$tabela&gh=0', function (data) {});
                        }
                    },
                    error: function (response) {
                        new PNotify({
                            title: 'Erro',
                            text: "Erro na consulta dos dados à Receita.<br>" +
                                    "Por favor aguarde cerca de um minuto e tente novamente ou preencha manualmente.",
                            type: 'warning',
                            styling: 'fontawesome',
                            nonblock: {
                                nonblock: false
                            },
                        });
                    }
                });
            }
        });
JS;
$this->registerJs($js);
$js = <<<JS
    $(document).ready(function () {

        //Quando o campo cep perde o foco.
        $("#orgao-cep").blur(function () {                  

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#orgao-logradouro").val("");
                $("#orgao-bairro").val("");
                $("#orgao-cidade").val("");
                $("#orgao-cidade").removeAttr("disabled");
                $("#orgao-uf").val("");
                $("#orgao-uf").removeAttr("disabled");
                $("#orgao-codigo_ibge").val("");
            } 
        
            krajeeDialog.confirm("Deseja atualizar os dados do endereço a partir do CEP informado?", function (result) {
                if (result) {     
        
                    //Nova variável "cep" somente com dígitos.
                    var cep = $("#orgao-cep").val().replace(/\D/g, '');

                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if (validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("#orgao-logradouro").val("...");
                            $("#orgao-bairro").val("...");
                            $("#orgao-cidade").val("...");
                            $("#orgao-uf").val("...");
                            $("#orgao-codigo_ibge").val("...");

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("#orgao-logradouro").val(dados.logradouro);
                                    $("#orgao-logradouro").css('background-color','antiquewhite');
                                    $("#orgao-bairro").val(dados.bairro);
                                    $("#orgao-bairro").css('background-color','antiquewhite');
                                    $("#orgao-cidade").val(dados.localidade);
                                    $("#orgao-cidade").css('background-color','antiquewhite');
                                    $('#orgao-uf').val(dados.uf);
                                    $("#orgao-uf").css('background-color','antiquewhite');
                                    $("#orgao-codigo_ibge").val(dados.ibge);
                                    $("#orgao-codigo_ibge").css('background-color','antiquewhite');
                                    $("#orgao-numero").focus(); 
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
