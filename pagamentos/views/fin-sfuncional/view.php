<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinSfuncionalController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\controllers\ContextMenusController;
use pagamentos\models\FinSfuncional;
use kartik\icons\FontAwesomeAsset;
use common\models\Listas;
use yii\widgets\MaskedInput;
use pagamentos\controllers\CadSfuncionalController;
use kartik\cmenu\ContextMenu;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncional */

$cv = isset(Yii::$app->request->queryParams['cv']) && !empty(Yii::$app->request->queryParams['cv']) ? explode(',', Yii::$app->request->queryParams['cv']) : null;

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
$mv = (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') || !isset(Yii::$app->request->queryParams['mv']) || FinSfuncional::STATUS_CANCELADO === $model->status || !$buttons ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'];


$this->title = FinSfuncionalController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME_PL, 'url' => ['/cad-servidores/']];
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($model->getCadServidor()->nome), 'url' => ['/cad-servidores/view', 'id' => $model->getCadServidor()->slug, 'mv' => $mv]];
$this->params['breadcrumbs'][] = ['label' => CadSfuncionalController::CLASS_VERB_NAME, 'url' => ['/cad-sfuncional/' . $model->getCadServidor()->matricula . '?mv=' . $mv]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="fin-sfuncional-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinSfuncional::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
//    $attributes[] = [
//                'attribute' => 'ano',
//                'format' => 'raw',
//                'value' => $model->ano,
//                'options' => ['readonly' => !$buttons],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];
//
//    $attributes[] = [
//                'attribute' => 'mes',
//                'format' => 'raw',
//                'value' => $model->mes,
//                'options' => ['readonly' => !$buttons],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];
//
//    $attributes[] = [
//                'attribute' => 'parcela',
//                'format' => 'raw',
//                'value' => $model->parcela,
//                'options' => ['readonly' => !$buttons],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];

    $attributes[] = [
        'attribute' => 'id_cad_cargos',
        'format' => 'raw',
        'value' => (($m_cad_cargos = \pagamentos\models\CadCargos::findOne($model->id_cad_cargos)) != null ) ? $m_cad_cargos->nome . '(' . $m_cad_cargos->id_cargo . ')' : null,
        'format' => 'raw',
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(
                    \pagamentos\models\CadCargos::find()
                            ->select([
                                'id' => 'id',
                                'nome' => 'CONCAT(nome, "(", LPAD(id_cargo, 4, "0"), ")", (IF (LENGTH(cbo) > 0, CONCAT(" (CBO: ",cbo,")"), CONCAT(" (CBO: Não informado)"))))',
                            ])
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->all(), 'id', 'nome'),
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;' . (is_array($cv) && in_array('id_cad_cargos', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
    ];

    $attributes[] = [
        'attribute' => 'id_cad_centros',
        'format' => 'raw',
        'value' => (($m_cad_centros = \pagamentos\models\CadCentros::findOne($model->id_cad_centros)) != null ) ? $m_cad_centros->nome_centro . '(' . $m_cad_centros->cod_centro . ')' : null,
        'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(
                    \pagamentos\models\CadCentros::find()
                            ->select([
                                'id' => 'id',
                                'nome' => 'CONCAT(nome_centro, "(", LPAD(cod_centro, 4, "0"), ")")',
                            ])
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->all(), 'id', 'nome'),
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;' . (is_array($cv) && in_array('id_cad_centros', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
    ];

    $attributes[] = [
        'attribute' => 'id_cad_departamentos',
        'format' => 'raw',
        'value' => (($m_cad_departamentos = \pagamentos\models\CadDepartamentos::findOne($model->id_cad_departamentos)) != null ) ? $m_cad_departamentos->departamento . '(' . $m_cad_departamentos->id_departamento . ')' : null,
        'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(
                    \pagamentos\models\CadDepartamentos::find()
                            ->select([
                                'id' => 'id',
                                'nome' => 'CONCAT(departamento, "(", LPAD(id_departamento, 4, "0"), ")")',
                            ])
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->all(), 'id', 'nome'),
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;' . (is_array($cv) && in_array('id_cad_departamentos', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
    ];
    $attributes[] = [
        'attribute' => 'id_pccs',
        'format' => 'raw',
        'value' => (($m_cad_pccs = \pagamentos\models\CadPccs::find()->where([
            'id_pccs' => $model->id_pccs,
            'dominio' => Yii::$app->user->identity->dominio,
        ])->one()) != null ) ? $m_cad_pccs->nome_pccs . '(' . $m_cad_pccs->nivel_pccs . ') PCCS(' . $m_cad_pccs->id_pccs . ')' : null,
        'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => ArrayHelper::map(
                    \pagamentos\models\CadPccs::find()
                            ->select([
                                'id' => 'id_pccs',
                                'nome' => 'CONCAT(nome_pccs, "(", nivel_pccs, ")", "(", LPAD(id_pccs, 4, "0"), ")")',
                            ])
                            ->where([
                                'dominio' => Yii::$app->user->identity->dominio,
                            ])->asArray()->all(), 'id', 'nome'),
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'vertical-align: middle;' . (is_array($cv) && in_array('id_pccs', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'desconta_irrf',
                'format' => 'raw',
                'value' => Listas::getSN($model->desconta_irrf),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'desconta_sindicato',
                'format' => 'raw',
                'value' => Listas::getSN($model->desconta_sindicato),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'previdencia',
                'format' => 'raw',
                'value' => Listas::getTipoFaixa($model->previdencia),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getTiposFaixa('previdencia'),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'attribute' => 'categoria_receita',
        'format' => 'raw',
        'value' => Listas::getCatRFB($model->categoria_receita),
        'type' => DetailView::INPUT_SELECT2,
        'widgetOptions' => [
            'data' => Listas::getCatRFBs(),
            'options' => ['placeholder' => 'Selecione ...'],
            'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
        ],
        'options' => [
            'readonly' => !$buttons,
            'placeholder' => 'Selecione ...'
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => (is_array($cv) && in_array('categoria_receita', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
    ];

//    $attributes[] = [
//        'columns' => [
//            [
//                'attribute' => 'lanca_anuenio',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_anuenio),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...',
//                    'id' => $lanca_anuenio = Yii::$app->security->generateRandomString(8),
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//            [
//                'attribute' => 'lanca_trienio',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_trienio),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...',
//                    'id' => $lanca_trienio = Yii::$app->security->generateRandomString(8),
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//        ],
//    ];
//
//    $attributes[] = [
//        'columns' => [
//            [
//                'attribute' => 'lanca_quinquenio',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_quinquenio),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...',
//                    'id' => $lanca_quinquenio = Yii::$app->security->generateRandomString(8),
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//            [
//                'attribute' => 'lanca_decenio',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_decenio),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...',
//                    'id' => $lanca_decenio = Yii::$app->security->generateRandomString(8),
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//        ],
//    ];
//    $attributes[] = [
//        'columns' => [
//            [
//                'attribute' => 'lanca_salario',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_salario),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...'
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//            [
//                'attribute' => 'lanca_funcao',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->lanca_funcao),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...'
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//        ],
//    ];
//    $attributes[] = [
//        'columns' => [
//            [
//                'attribute' => 'n_faltas',
//                'format' => 'raw',
//                'value' => $model->n_faltas,
//                'type' => DetailView::INPUT_SPIN,
//                'widgetOptions' => [
////                    'class' => kartik\touchspin\TouchSpin::classname(),
//                    'pluginOptions' => [
//                        'verticalbuttons' => false,
//                        'verticalup' => '<i class="fas fa-plus"></i>',
//                        'verticaldown' => '<i class="fas fa-minus"></i>'
//                    ],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'class' => 'form-control mr-0 ml-0',
//                    'placeholder' => 'Dias de falta entre 0 e 31...',
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//            [
//                'attribute' => 'decimo_aniv',
//                'format' => 'raw',
//                'value' => Listas::getSN($model->decimo_aniv),
//                'type' => DetailView::INPUT_SELECT2,
//                'widgetOptions' => [
//                    'data' => Listas::getSNs(),
//                    'options' => ['placeholder' => 'Selecione ...'],
//                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
//                ],
//                'options' => [
//                    'readonly' => !$buttons,
//                    'placeholder' => 'Selecione ...'
//                ],
//                'displayOnly' => !$buttons,
//                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//            ],
//        ],
//    ];


    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'situacao',
                'value' => Listas::getSituacaoCadastral($model->situacao),
                'format' => 'raw',
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSituacoesCadastrais(),
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !Yii::$app->user->identity->administrador >= 1,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !Yii::$app->user->identity->administrador >= 1,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;' . (is_array($cv) && in_array('situacao', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
            ],
            [
                'attribute' => 'situacaofuncional',
                'format' => 'raw',
                'value' => Listas::getSituacaoFuncional($model->situacaofuncional),
                'format' => 'raw', 'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSituacoesFuncionais(),
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;' . (is_array($cv) && in_array('situacaofuncional', $cv) ? 'background-color:rgba(243, 18, 18, 0.35);' : '')]
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'previdencia',
                'format' => 'raw',
                'value' => Listas::getTipoPrevidencia($model->previdencia),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getTiposPrevidencia(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'tipobeneficio',
                'format' => 'raw',
                'value' => Listas::getTipoBeneficio($model->tipobeneficio),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getTiposBeneficio(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
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
                'attribute' => 'n_faltas',
                'format' => 'raw',
                'value' => $model->n_faltas,
                'type' => DetailView::INPUT_SPIN,
                'widgetOptions' => [
//                    'class' => kartik\touchspin\TouchSpin::classname(),
                    'pluginOptions' => [
                        'verticalbuttons' => false,
                        'verticalup' => '<i class="fas fa-plus"></i>',
                        'verticaldown' => '<i class="fas fa-minus"></i>',
                        'buttondown_class' => 'btn btn-outline-secondary bootstrap-touchspin-down mr-0',
                    ],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                    'placeholder' => 'Dias de falta entre 0 e 31...',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'n_horaaula',
                'format' => 'raw',
                'value' => $model->n_horaaula,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'mask' => '99'
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'n_adnoturno',
                'format' => 'raw',
                'value' => $model->n_adnoturno,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'mask' => '99'
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
    $app = Url::home(true) . 'cad-sfuncional/z/' . $model->getCadServidor()->matricula . '?c=carga_horaria';
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'n_hextra',
                'format' => 'raw',
                'value' => $model->n_hextra,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'mask' => '99'
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'ponto',
                'format' => 'raw',
                'value' => $model->ponto,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'mask' => '999'
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'class' => 'form-control',
                    'id' => $ponto_id = Yii::$app->security->generateRandomString(8),
//                    'onblur' => "    
//                            var value = $( this ).val();
////                            krajeeDialog.alert('$app');
//    
//$.get( '$app', function( data ) {
//  alert( data );
//});
//
////                        $.get('$app', function(data){
////                            krajeeDialog.alert(data.retorno);
//////                            if ( $.this().val < (data * 4)
//////                            $('#msgs-link').val('https://'+data);
////                        });
//                    ",
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'lanca_salario',
                'format' => 'raw',
                'value' => Listas::getSN($model->lanca_salario),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => Listas::getSNs(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...',
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'enio',
                'format' => 'raw',
                'value' => FinSfuncionalController::getEnioLabel($model->enio),
                'type' => DetailView::INPUT_SELECT2,
                'widgetOptions' => [
                    'data' => FinSfuncionalController::getListaEnios(),
                    'options' => ['placeholder' => 'Selecione ...'],
                    'pluginOptions' => ['allowClear' => true, 'width' => '100%'],
                ],
                'options' => [
                    'readonly' => !$buttons,
                    'placeholder' => 'Selecione ...'
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'group' => true,
        'label' => 'Retorno bancário',
        'rowOptions' => ['class' => 'table-info text-center'],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'retorno_ocorrencia',
                'format' => 'raw',
                'value' => $model->retorno_ocorrencia,
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'retorno_documento',
                'format' => 'raw',
                'value' => $model->retorno_documento,
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'retorno_data',
                'format' => 'raw',
                'value' => $model->retorno_data,
                'type' => DetailView::INPUT_WIDGET,
                'widgetOptions' => [
                    'class' => MaskedInput::classname(),
                    'clientOptions' => [
                        'alias' => 'date',
                    ],
                ],
                'options' => [
                    'class' => 'form-control',
                ],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'retorno_valor',
                'format' => 'raw',
                'value' => $model->retorno_valor,
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
                    'class' => 'form-control',
                ],
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
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
            <?= $this->render('v_widget1', ['model' => $model, 'mv' => $mv]) ?>            
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
                'mode' => !Yii::$app->user->identity->base_servico == 'pagamentos' || !isset(Yii::$app->request->queryParams['mv']) || FinSfuncional::STATUS_CANCELADO == $model->status || !$sf || ($model->getCadServidor()->getFalecimento() !== null && !Yii::$app->user->identity->gestor) ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
                'buttons1' => Yii::$app->user->identity->base_servico == 'pagamentos' ? (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0' && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() !== null) ? '' : ($buttons && $sf && $model->getCadServidor($model->id_cad_servidores)->getFalecimento() == null ? '{update}' : $label_statusCancelado) : '',
                'deleteOptions' => [
                    'params' => ['id' => $model->id],
                    'url' => ['dlt', 'id' => $model->id],
                ], 
                'panel' => [
                    'heading' => FinSfuncionalController::CLASS_VERB_NAME . ' (' . $model->getCadServidor()->matricula .
                    (Yii::$app->user->identity->administrador >= 2 ? (' [ID: ' . $model->getCadServidor()->id . ']') : '') . ')' .
                    (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $model->id . ')') : ''),
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
//$js = <<<JS
//        
//    $('#$ponto_id').blur(function(){
//        var value = $( this ).val();
////        krajeeDialog.alert('$app');
//    });
//    $('#$lanca_decenio').change(function(){
////        alert($(this).val());
////        if ($(this).val() == 1){
//            $('#$lanca_anuenio').val($(this).val()); 
//            $('#$lanca_trienio').val($(this).val());
//            $('#$lanca_quinquenio').val($(this).val());
////        }
//    });
//JS;
//$this->registerJs($js);
?>
