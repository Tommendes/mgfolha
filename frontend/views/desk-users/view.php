<?php

use yii\helpers\Url;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use frontend\controllers\SisEventsController;
use common\models\SisParams;
use frontend\controllers\DeskUsersController;
use frontend\models\DeskUsers;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
$status = [
    SisParams::STATUS_INATIVO => 'Inativo',
    SisParams::STATUS_ATIVO => 'Ativo',
];
$evento = SisEventsController::findEvt($model->evento);

/* @var $this yii\web\View */
/* @var $model frontend\models\DeskUsers */

$this->title = DeskUsersController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = ['label' => DeskUsersController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="desk-users-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == frontend\models\DeskUsers::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $buttons = true;
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'id',
                'value' => $model->id,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'status',
                'value' => $model->status,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'status_desk',
                'value' => $model->status_desk,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'dll_cod',
                'value' => $model->dll_cod,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'dll_cnpj',
                'value' => $model->dll_cnpj,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'dll_nome',
                'value' => $model->dll_nome,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cli_nome',
                'value' => $model->cli_nome,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'columns' => [
            [
                'attribute' => 'cli_nome_comput',
                'value' => $model->cli_nome_comput,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
            [
                'attribute' => 'cli_nome_user',
                'value' => $model->cli_nome_user,
                'format' => 'raw',
                'options' => [
                    'readonly' => !$buttons,
                ],
                'displayOnly' => !$buttons,
                'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
            ],
        ],
    ];
    $attributes[] = [
        'attribute' => 'versao_desk',
        'value' => $model->versao_desk,
        'format' => 'raw',
        'options' => [
            'readonly' => !$buttons,
        ],
        'displayOnly' => !$buttons,
        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
    ];
    $attributes[] = [
        'group' => true,
        'label' => $model->getAttributeLabel('evento') . ' ' . (
        Yii::$app->user->identity->gestor ?
        Html::button($evento, [
            'value' => Url::to(['/eventos/' . $model->evento]),
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
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || DeskUsers::STATUS_CANCELADO === $model->status ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
        'deleteOptions' => [
            'params' => ['id' => $model->id],
            'url' => ['dlt', 'id' => $model->id],
        ],
        'panel' => [
            'heading' => DeskUsersController::CLASS_VERB_NAME,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => $attributes,
    ])
    ?>

</div>
