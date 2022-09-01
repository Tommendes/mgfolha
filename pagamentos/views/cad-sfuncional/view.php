<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadSfuncionalController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadSfuncional;
use kartik\icons\FontAwesomeAsset;
use common\models\Listas;
use pagamentos\models\CadServidores;
use yii\widgets\MaskedInput;
use kartik\dialog\Dialog;
use pagamentos\controllers\FinSfuncionalController;
use kartik\cmenu\ContextMenu;
use pagamentos\controllers\ContextMenusController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSfuncional */

// Armazena a situação da folha 
$sf = 0;
if (!Yii::$app->user->isGuest) {
// Armazena em parâmetro a situação da folha
    $sf = pagamentos\controllers\FinParametrosController::getS();
}
FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

$buttons = Yii::$app->user->identity->cadastros >= 3;
$mv = (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || !isset(Yii::$app->request->queryParams['mv']) || CadSfuncional::STATUS_CANCELADO === $model->status || !$buttons || !$sf ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'];

$financeiro = \pagamentos\models\FinSfuncional::find()->where([
            'id_cad_servidores' => $model->id_cad_servidores,
            'dominio' => $model->dominio,
            'ano' => Yii::$app->user->identity->per_ano,
            'mes' => Yii::$app->user->identity->per_mes,
            'parcela' => Yii::$app->user->identity->per_parcela,
        ])->one();

$this->title = CadSfuncionalController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME_PL, 'url' => ['/cad-servidores/']];
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug, 'mv' => $mv]];
$this->params['breadcrumbs'][] = ['label' => FinSfuncionalController::CLASS_VERB_NAME, 'url' => (!empty($financeiro->slug)) ? ['/fin-sfuncional/' . $model->getCadServidor()->matricula . '?mv=' . $mv] : 'javascript:naF("financeiro");'];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
$label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadSfuncional::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
?>
<div class="cad-sfuncional-view">
    <?php
    $attributes[] = [
        'attribute' => 'id_local_trabalho',
        'format' => 'raw',
        'value' => (($m_local = \pagamentos\models\CadLocaltrabalho::findOne($model->id_local_trabalho)) != null ) ? $m_local->nome . '(' . $m_local->id_local_trabalho . ')' : null,
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(
                    \pagamentos\models\CadLocaltrabalho::find()
                            ->select([
                                'id' => 'id',
                                'nome' => 'CONCAT(nome, "(", LPAD(id_local_trabalho, 4, "0"), ")", (IF (LENGTH(siope) > 0, CONCAT(" (SIOPE: ",siope,")"), CONCAT(" (SIOPE: Não informado)"))))',
                            ])
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->all(), 'id', 'nome'),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
    ];
    $cadastro_p = (($cadastro_p = CadServidores::findOne($model->id_cad_principal)) != null ?
            $cadastro_p->matricula : 'Não há outra matrícula');
    $vinculoPertence = CadSfuncional::find()
            ->join('join', $cad = CadServidores::tableName(), $cad . '.id = ' . CadSfuncional::tableName() . '.id_cad_principal')
            ->where([
                $cad . '.id' => $model->id_cad_principal,
                $cad . '.dominio' => Yii::$app->user->identity->dominio
            ])
//            ->andWhere(['!=', $model->id_cad_principal, $model->id_cad_servidores])
            ->all();
    $matriculasVinculadas = '';
    $vinculos = CadServidores::find()
            ->where([
                'cpf' => $model->getCadServidor()->cpf,
                'dominio' => Yii::$app->user->identity->dominio
            ])
            ->andWhere(['!=', 'id', $model->id_cad_principal])
            ->all();
    $urlCA = Url::home(true) . 'cad-sfuncional/a-c/' . $model->slug . '?campo=id_cad_principal&valor=';
    if (count($vinculos) > 0) {
        foreach ($vinculos as $vinculo) {
            $matriculasVinculadas .= ' ' . $vinculo->matricula;
            $urlCAThis = $urlCA . $vinculo->id;
            $cad_principal_itens[] = [
                'id' => $idbtn = 'cust-btn-' . $vinculo->id,
                'label' => $vinculo->matricula,
                'cssClass' => 'btn-primary',
                'icon' => null,
                'action' => new JsExpression('function(dialog) {
                    krajeeDialog.confirm("Confirma a mudança do vínculo principal para este servidor?", function (result) {
                        if (result) { // ok button was pressed
                            $.ajax({
                                url: "' . $urlCAThis . '",
                                type: "post",
                                success: function (response) {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: "Sucesso",
                                        text: response["mess"],
                                        type: response["class"],
                                        styling: "fontawesome",
                                        animate: {
                                            animate: true,
                                            in_class: "rotateInDownLeft",
                                            out_class: "rotateOutUpRight"
                                        }
                                    });
                                    if (response["reloadPage"] == true) {
                                        javascript:location.reload();
                                    }
                                },
                            });
                        }
                    });
                }')
            ];
        }
        if ($model->id_cad_principal != $model->id) {
            $urlCAThis = $urlCA . $model->id_cad_servidores;
            $cad_principal_itens[] = [
                'id' => 'cust-btn-' . $model->id_cad_servidores,
                'label' => 'Remover matrícula diferente',
                'cssClass' => 'btn-outline-secondary',
                'icon' => 'fas fa-ban',
                'action' => new JsExpression("function(dialog) {
                    krajeeDialog.confirm('Confirma a exclusão da matricula principal diferente?', function (result) {
                            if (result) { // ok button was pressed
                                $.ajax({
                                    url: '$urlCAThis',
                                    type: 'post',
                                    success: function (response) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Sucesso',
                                            text: response['mess'],
                                            type: response['class'],
                                            styling: 'fontawesome',
                                            animate: {
                                                animate: true,
                                                in_class: 'rotateInDownLeft',
                                                out_class: 'rotateOutUpRight'
                                            }
                                        });
                                        if (response['reloadPage'] == true) {
                                            javascript:location.reload();
                                        }
                                    },
                                });
                            }
                        });
                    }")
            ];
        }
    } else {
        $cad_principal_itens = [];
    }
    if (count($cad_principal_itens) == 1) {
        $cad_principal_add = [
            'id' => 'cust-btn-' . $model->id,
            'label' => 'Fechar',
            'cssClass' => 'btn-outline-secondary',
            'icon' => null,
            'action' => new JsExpression("function(dialog) {
                        dialog.close();
                    }")
        ];
        array_push($cad_principal_itens, $cad_principal_add);
    }
    echo Dialog::widget([
        'libName' => 'btn_cad_principal', // a custom lib name
        'options' => [// customized BootstrapDialog options
            'size' => Dialog::SIZE_NORMAL,
            'type' => Dialog::TYPE_INFO,
            'title' => 'Cadastro principal',
            'buttons' => $cad_principal_itens,
        ]
    ]);
    $attributes[] = [
        'columns' => [
            [
//                'attribute' => 'id_cad_principal',
                'label' => $model->getAttributeLabel('id_cad_principal') . ($model->id_cad_principal != $model->id ? ' ' . Html::a('<span class="fa fa-eye"></span>', Url::to(['cad-servidores/' . $model->getCadServidor($model->id_cad_principal)->slug]), [
                    'title' => 'Clique para ver o registro principal',
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                ]) : ''),
                'format' => 'raw',
                'value' => (count($vinculoPertence) > 1 && $model->id_cad_principal == $model->id_cad_servidores) ? Html::button($cadastro_p, [
                    'class' => 'btn btn-secondary w-100',
                    'title' => 'Este registro é o vinculo principal da' . (count($vinculos) > 1 ? 's' : '') . ' matrícula' . (count($vinculos) > 1 ? 's' : '') . $matriculasVinculadas . '! Não pode ser alterado.',
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                    'style' => 'padding: 2px;',
                ]) : Html::button($cadastro_p, [
                    'class' => 'btn btn-secondary w-100',
                    'title' => count($vinculos) > 0 ?
                    'Clique para informar uma matrícula diferente da atual como '
                    . 'sendo a principal deste servidor (Esta informação é opcional)' :
                    'Não há outra matrícula com o CPF deste servidor! Não é possível alterar.',
                    'data-toggle' => "tooltip",
                    'data-placement' => "bottom",
                    'style' => 'padding: 2px;',
                    'onclick' => count($vinculos) > 0 ? '
                        btn_cad_principal.dialog(
                            "Escolha abaixo uma matrícula como sendo a principal.",
                            function(result) {
                                // do something
                            }
                        );
                    ' : '',
                ]),
                'options' => [
                    'style' => 'padding: 0px;',
                ],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'id_escolaridade',
                'format' => 'raw',
                'value' => Listas::getEscolaridade($model->id_escolaridade),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getEscolaridades(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'escolaridaderais',
        'format' => 'raw',
        'value' => Listas::getEscolaridadeRais($model->escolaridaderais),
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => Listas::getEscolaridadesRais(),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'rais',
                'format' => 'raw',
                'value' => Listas::getSN($model->rais),
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
                'attribute' => 'dirf',
                'format' => 'raw',
                'value' => Listas::getSN($model->dirf),
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
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'sefip',
                'format' => 'raw',
                'value' => Listas::getSN($model->sefip),
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
                'attribute' => 'sicap',
                'format' => 'raw',
                'value' => Listas::getSN($model->sicap),
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
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'insalubridade',
                'format' => 'raw',
                'value' => Listas::getSN($model->insalubridade),
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
                'attribute' => 'decimo',
                'format' => 'raw',
                'value' => Listas::getSN($model->decimo),
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
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_vinculo',
                'format' => 'raw',
                'value' => Listas::getVinculo($model->id_vinculo),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getVinculos(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'id_cat_sefip',
                'format' => 'raw',
                'value' => Listas::getCatSefip($model->id_cat_sefip),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getCatsSefip(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'ocorrencia',
        'format' => 'raw',
        'value' => Listas::getOcorrencia($model->ocorrencia),
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => Listas::getOcorrencias(),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'molestia',
                'format' => 'raw',
                'value' => Listas::getMolestia($model->molestia),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getMolestias(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'd_laudomolestia',
                'format' => 'raw',
                'value' => $model->d_laudomolestia,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'manad_tiponomeacao',
                'format' => 'raw',
                'value' => Listas::getNomeacao($model->manad_tiponomeacao),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getNomeacoes(),
                    'options' => ['prompt' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'manad_numeronomeacao',
                'format' => 'raw',
                'value' => $model->manad_numeronomeacao,
                'options' => ['readonly' => !$buttons, 'placeholder' => (!is_null($model->manad_tiponomeacao) && $model->manad_tiponomeacao >= 1) ? 'Informar ' . strtolower(Listas::getNomeacao($model->manad_tiponomeacao)) : 'Número nomeação'],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'd_tempo',
                'format' => 'raw',
                'value' => $model->d_tempo,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'd_tempofim',
                'format' => 'raw',
                'value' => $model->d_tempofim,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'd_beneficio',
                'format' => 'raw',
                'value' => $model->d_beneficio,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'n_valorbaseinss',
                'format' => 'raw',
                'value' => $model->n_valorbaseinss,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'decimal',
                        'digits' => 2,
                        'digitsOptional' => false,
                        'radixPoint' => '.',
                        'groupSeparator' => '',
                        'autoGroup' => true,
                        'removeMaskOnSubmit' => true,
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                    'id' => $id_n_valorbaseinss = Yii::$app->security->generateRandomString(8),
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'carga_horaria',
        'format' => 'raw',
        'value' => $model->carga_horaria,
        'options' => ['readonly' => !$buttons],
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
    <div class="row">
        <?php if ($model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) { ?>
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <h2><p class="text-center">Este servidor faleceu em <?= $model->getCadServidor($model->id_cad_servidores)->getFalecimento()->d_afastamento ?><br>Algumas funções estão desabilitadas para este registro</p></h2>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-3">
            <?= $this->render('/fin-sfuncional/v_widget1', ['model' => $model, 'mv' => $mv]) ?>            
        </div>
        <div class="col-md-9">
            <?php
            $baseServico = Yii::$app->user->identity->base_servico;
            if ($baseServico == 'pagamentos') {
                ContextMenu::begin(['items' => ContextMenusController::getItemsImpressServidor($model->id_cad_servidores), 'options' => ['id' => 'ctxmImpressao',]]);
            }
            ?>  
            <?=
            DetailView::widget([
                'model' => $model,
                'condensed' => false,
                'hover' => true,
                'mode' => !Yii::$app->user->identity->base_servico == 'pagamentos' || !isset(Yii::$app->request->queryParams['mv']) || CadSfuncional::STATUS_CANCELADO === $model->status || !$sf || $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0' && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update}' : $label_statusCancelado) : '',
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ],
                'panel' => [
                    'heading' => CadSfuncionalController::CLASS_VERB_NAME . ' (' . $model->getCadServidor()->matricula .
                    (Yii::$app->user->identity->administrador >= 2 ? (' [ID: ' . $model->getCadServidor()->id . ']') : '') . ')' . ')',
                    'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
                ],
                'attributes' => $attributes,
            ])
            ?>
            <?php
            if ($baseServico == 'pagamentos') {
                ContextMenu::end();
            }
            ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
    $(document).on('input', '#$id_n_valorbaseinss', function() {
        $(this).val($(this).val().replace(',', '.'));
    });
JS;
//$this->registerJs($js);
?>

