<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use frontend\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\FinFaixasController;
use pagamentos\models\FinFaixas;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinFaixas */

$this->title = FinFaixasController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => FinFaixasController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fin-faixas-view">

    <!--    <h1>Html::encode($this->title) ?></h1>
    
        <p>
            Html::a(, ['u', ], ['class' => 'btn btn-primary']) ?>
            Html::a(, ['dlt', ], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>-->

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\FinFaixas::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'attribute' => 'tipo',
        'format' => 'raw',
        'value' => $model->tipo,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'data',
        'format' => 'raw',
        'value' => $model->data,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'faixa',
        'format' => 'raw',
        'value' => $model->faixa,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_final1',
        'format' => 'raw',
        'value' => $model->v_final1,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_faixa1',
        'format' => 'raw',
        'value' => $model->v_faixa1,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_deduzir1',
        'format' => 'raw',
        'value' => $model->v_deduzir1,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_final2',
        'format' => 'raw',
        'value' => $model->v_final2,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_faixa2',
        'format' => 'raw',
        'value' => $model->v_faixa2,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_deduzir2',
        'format' => 'raw',
        'value' => $model->v_deduzir2,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_final3',
        'format' => 'raw',
        'value' => $model->v_final3,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_faixa3',
        'format' => 'raw',
        'value' => $model->v_faixa3,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_deduzir3',
        'format' => 'raw',
        'value' => $model->v_deduzir3,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_final4',
        'format' => 'raw',
        'value' => $model->v_final4,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_faixa4',
        'format' => 'raw',
        'value' => $model->v_faixa4,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_deduzir4',
        'format' => 'raw',
        'value' => $model->v_deduzir4,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_final5',
        'format' => 'raw',
        'value' => $model->v_final5,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_faixa5',
        'format' => 'raw',
        'value' => $model->v_faixa5,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'v_deduzir5',
        'format' => 'raw',
        'value' => $model->v_deduzir5,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'deduzir_dependente',
        'format' => 'raw',
        'value' => $model->deduzir_dependente,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'salario_vigente',
        'format' => 'raw',
        'value' => $model->salario_vigente,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'inss_teto',
        'format' => 'raw',
        'value' => $model->inss_teto,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'inss_patronal',
        'format' => 'raw',
        'value' => $model->inss_patronal,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'inss_rat',
        'format' => 'raw',
        'value' => $model->inss_rat,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'inss_fap',
        'format' => 'raw',
        'value' => $model->inss_fap,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];

    $attributes[] = [
        'attribute' => 'rpps_patronal',
        'format' => 'raw',
        'value' => $model->rpps_patronal,
        'options' => ['readonly' => !$buttons],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
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
//    $attributes[] = [
//        'attribute' => 'evento',
//      'value' => Yii::$app->user->identity->gestor ?
//      Html::button($evento, [
//          'value' => Url::to(['/sis-events/' . $model->evento]),
//          'title' => Yii::t('yii', 'Ver evento'),
//          'class' => 'showModalButton button-as-link'
//      ]) : $evento,
//      'format' => 'raw',
//      'label' => 'Último evento',
//      'displayOnly' => true,
//  ];
//  $attributes[] = [
//      'columns' => [
//          [
//              'attribute' => 'created_at',
//              'format' => 'raw',
//              'value' => date('d-m-Y H:i:s', $model->created_at),
//              'options' => ['readonly' => true],
//              'displayOnly' => true,
//              'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//          ],
//          [
//              'attribute' => 'updated_at',
//              'format' => 'raw',
//              'value' => date('d-m-Y H:i:s', $model->updated_at),
//              'options' => ['readonly' => true],
//              'displayOnly' => true,
//              'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//          ],
//      ]
//  ];
//  
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || FinFaixas::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => FinFaixasController::CLASS_VERB_NAME . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $model->id . ')') : ''),
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
