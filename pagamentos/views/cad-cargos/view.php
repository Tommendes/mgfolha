<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use pagamentos\controllers\SisEventsController;
use common\models\SisParams;
use pagamentos\controllers\CadCargosController;
use pagamentos\models\CadCargos;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCargos */

$this->title = CadCargosController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => CadCargosController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cad-cargos-view">
    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == pagamentos\models\CadCargos::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
//    $attributes[] = [
//        'attribute' => 'id',
//        'format' => 'raw',
//        'value' => $model->id,
//        'options' => ['readonly' => !$buttons],
//        'displayOnly' => !$buttons,
//        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];
//
//    $attributes[] = [
//        'attribute' => 'slug',
//        'format' => 'raw',
//        'value' => $model->slug,
//        'options' => ['readonly' => !$buttons],
//        'displayOnly' => !$buttons,
//        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];
//
//    $attributes[] = [
//        'attribute' => 'status',
//        'format' => 'raw',
//        'value' => $model->status,
//        'options' => ['readonly' => !$buttons],
//        'displayOnly' => !$buttons,
//        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];
//
//    $attributes[] = [
//        'attribute' => 'dominio',
//        'format' => 'raw',
//        'value' => $model->dominio,
//        'options' => ['readonly' => !$buttons],
//        'displayOnly' => !$buttons,
//        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
//    ];

    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id_cargo',
                'format' => 'raw',
                'value' => $model->id_cargo,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'nome',
                'format' => 'raw',
                'value' => $model->nome,
                'options' => ['readonly' => !$buttons],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ]
        ]
    ];

    $attributes[] = [
        'attribute' => 'cbo',
        'format' => 'raw',
        'value' => $model->cbo,
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
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || CadCargos::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => CadCargosController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
