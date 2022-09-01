<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadSdependentesController;
use pagamentos\models\CadSdependentes;
use kartik\icons\FontAwesomeAsset;
use pagamentos\controllers\AppController;
use yii\widgets\MaskedInput;
use common\models\Listas;
use pagamentos\controllers\CadServidoresController;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSdependentes */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}

$this->title = CadSdependentesController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor($model->id_cad_servidores)->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor($model->id_cad_servidores)->slug]];
$this->params['breadcrumbs'][] = ['label' => CadSdependentesController::CLASS_VERB_NAME_PL . ' do servidor', 'url' => ['index', 'id_cad_servidores' => $model->id_cad_servidores]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cad-sdependentes-view">
    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadSdependentes::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cpf',
                'value' => AppController::setCpfCnpjMask($model->cpf),
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::className(),
                    'name' => 'input-telefone',
                    'mask' => ['999.999.999-99'],
                    'options' => [
                    ],
                ],
                'options' => [
                    'id' => $id_cpf = Yii::$app->security->generateRandomString(8),
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'nome',
                'value' => $model->nome,
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'rg',
                'value' => $model->rg,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'rg_d',
                'value' => $model->rg_d,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'rg_emissor',
                'value' => $model->rg_emissor,
                'options' => [
                    'readonly' => !$buttons,
                    'style' => 'text-transform:uppercase;',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'rg_uf',
                'value' => Listas::getUf($model->rg_uf),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getUfs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'tipo',
                'value' => Listas::getTipoDependencia($model->tipo),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getTiposDependencia(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'permanente',
                'value' => Listas::getSN($model->permanente),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'nascimento_d',
                'value' => $model->nascimento_d,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                    'id' => $id_nascimento_d = Yii::$app->security->generateRandomString(8),
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'inss_prz',
                'value' => $model->inss_prz,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                    'id' => $id_inss_prz = Yii::$app->security->generateRandomString(8),
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'rpps_prz',
                'value' => $model->rpps_prz,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                    'id' => $id_rpps_prz = Yii::$app->security->generateRandomString(8),
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'irrf_prz',
                'value' => $model->irrf_prz,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                    'id' => $id_irrf_prz = Yii::$app->security->generateRandomString(8),
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'certidao',
                'value' => $model->certidao,
                'options' => [
                    'readonly' => !$buttons,
                    'style' => 'text-transform:uppercase;',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'livro',
                'value' => $model->livro,
                'options' => [
                    'readonly' => !$buttons,
                    'style' => 'text-transform:uppercase;',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'folha',
                'value' => $model->folha,
                'options' => [
                    'readonly' => !$buttons,
                    'style' => 'text-transform:uppercase;',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'certidao_d',
                'value' => $model->certidao_d,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'carteira_vacinacao',
                'value' => Listas::getSN($model->carteira_vacinacao),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'historico_escolar',
                'value' => Listas::getSN($model->historico_escolar),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'plano_saude',
                'value' => Listas::getSN($model->plano_saude),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'pensionista',
                'value' => Listas::getSN($model->pensionista),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ]
    ];
    $attributes[] = [
        'attribute' => 'irpf',
        'value' => Listas::getSN($model->irpf),
        'format' => 'raw',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => Listas::getSNs(),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'onchange' => new \yii\web\JsExpression("if($('#cadsdependentes-irpf').val() == 1 && $('#$id_cpf').val().replace( /[^\d.]/g, '' ).length < 11){"
                    . "PNotify.removeAll();"
                    . "new PNotify({"
                    . "title: 'Aviso', "
                    . "text: 'Obrigatório informar o CPF do dependente',"
                    . "type: 'warning',"
                    . "styling: 'fontawesome',"
                    . "nonblock: {nonblock: false},"
                    . "});"
                    . "$('#" . $id_cpf . "').addClass( 'required' );"
                    . "$('#" . $id_cpf . "').focus();"
                    . "} else {"
                    . "$('#" . $id_cpf . "').removeClass( 'required' );"
                    . "}")
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;']
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
    <?php
    $mode = DetailView::MODE_VIEW;
    if (Yii::$app->user->identity->base_servico === 'pagamentos' &&
            isset(Yii::$app->request->queryParams['mv']) &&
            !empty(Yii::$app->request->queryParams['mv']) &&
            !(CadSdependentes::STATUS_CANCELADO === $model->status) &&
            $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null) {
        $mode = (Yii::$app->request->queryParams['mv'] == 1 || Yii::$app->request->queryParams['mv'] == DetailView::MODE_EDIT) ? DetailView::MODE_EDIT : DetailView::MODE_VIEW;
    }
    $buttons1 = '';
    if (Yii::$app->user->identity->base_servico === 'pagamentos' &&
            !(CadSdependentes::STATUS_CANCELADO === $model->status) &&
            $sf && !$modal &&
            $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null) {
        $buttons1 = '{update} {delete}';
    } else if (Yii::$app->user->identity->base_servico === 'pagamentos' &&
            (CadSdependentes::STATUS_CANCELADO === $model->status) &&
            $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null) {
        $buttons1 = $label_statusCancelado;
    }
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => $mode,
        'buttons1' => $buttons1,
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadSdependentesController::CLASS_VERB_NAME . ' (' . $model->matricula . ') de ' . AppController::tratar_nome($model->getCadServidor($model->id_cad_servidores)->nome) . ' (' . $model->getCadServidor($model->id_cad_servidores)->matricula . ')',
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
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
                            } else { 
                            }
                        });
                    } else {                        
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
//
//<?php
//$url_cpf = Url::to(['cad-sdependentes/r?q=']);
//$url_id_cad_servidores = '&id_cad_servidores=' . $model->id_cad_servidores;
//$url_id = '&i=' . $model->id;
//$js = <<< JS
//    $('#$id_cpf').focusout(function () {        
//        var str = $(this).val();
//        var url_get = '$url_cpf' + str + '$url_id_cad_servidores'+'$url_id';
//        var tamanho = $(this).val().length;
//        if (tamanho >= 11){
//            var resultado = 0;
//            var cadas_res = '<ol>', sl, rs, nm;
//            $.ajax({
//                url: url_get,
//                success: function (json) {
//                    var obj = jQuery.parseJSON( json );
//                    if (obj.id > 0) {
//                        $.each(obj.result, function() {
//                            $.each(this, function(i, data) {
//                                sl = data; 
//                            });
//                            cadas_res = cadas_res + '<li>' + sl + '</li>';
//                        });        
//                        krajeeDialog.alert(
//                            "<p>Este CPF já pertence a outro dependente conforme abaixo.</p>" 
////                            + "<p>Continuar com este registro corre por responsabilidade do usuário.</p>" 
////                            + "<p><strong>O cadastro iniciado não poderá ser salvo com este CPF.</strong></p>" 
//                            + cadas_res + "</ol>");   
//                    } else {                        
//                    }
//                }
//            });            
//        }
//    });
//JS;
//$this->registerJs($js);
