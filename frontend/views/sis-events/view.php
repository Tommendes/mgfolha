<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\detail\DetailView;
use frontend\controllers\SisEventsController;
use common\models\SisEvents;
use kartik\icons\FontAwesomeAsset;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\InfoWindow;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\SisEvents */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'SisEvents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="eventos-view">

    <?php
    $label_statusCancelado = Html::icon('warning-sign') . ' ' . ($model->status == common\models\SisEvents::STATUS_CANCELADO ? 'Registro cancelado >>> Não pode ser editado' : '');
    $this->title = frontend\controllers\SisEventsController::CLASS_VERB_NAME;
    $buttons = true;
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'condensed' => false,
        'hover' => true,
        'mode' => !isset(Yii::$app->request->queryParams['mv']) || SisEvents::STATUS_CANCELADO === $model->status_params ? DetailView::MODE_VIEW : Yii::$app->request->queryParams['mv'],
        'buttons1' => false, //(isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] === '0') ? '' : ($buttons ? '{update} {delete}' : $label_statusCancelado),
//        'deleteOptions' => [
//            'params' => ['id' => $model->id],
//            'url' => ['dlt', 'id' => $model->id],
//        ],
        'panel' => [
            'heading' => SisEventsController::CLASS_VERB_NAME . ' ' . $model->id,
            'type' => $buttons ? DetailView::TYPE_INFO : DetailView::TYPE_WARNING,
        ],
        'attributes' => [
//            'slug',
//            'status',
//            'dominio',
            'evento:ntext',
            [
                'columns' => [
                    [
                        'attribute' => 'classevento',
                        'value' => SisEventsController::getEventoDescri($model->classevento),
                        'format' => 'raw',
                        'options' => ['readonly' => false,],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'tabela_bd',
                        'format' => 'raw',
                        'options' => ['readonly' => false,],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'geo_lt',
                        'format' => 'raw',
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'geo_ln',
                        'format' => 'raw',
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute' => 'id_user',
                        'value' => \common\models\User::findOne($model->id_user)->username,
                        'format' => 'raw',
                        'options' => ['readonly' => false,],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                    [
                        'attribute' => 'ip',
                        'format' => 'raw',
                        'options' => ['readonly' => false,],
                        'displayOnly' => true,
                        'valueColOptions' => ['style' => 'width:30%;vertical-align: middle;']
                    ],
                ],
            ], [
                'group' => true,
                'label' => (Html::a('Ver todo o histórico do registro', Url::to(['/sis-events?SisEventsSearch%5Btabela_bd%5D=' . $model->tabela_bd . '&SisEventsSearch%5Bid_registro%5D=' . $model->id_registro]), [// . '&modal=1'
                    'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => SisEventsController::CLASS_VERB_NAME_PL]),
                    'class' => 'button-as-link w-100',
                    'onclick' => 'javascript:location.reload();',
                ])) . ' | ' . $model->getAttributeLabel('created_at') . ' ' . date('d-m-Y H:i:s', $model->created_at)
                . ' | ' . $model->getAttributeLabel('updated_at') . ' ' . date('d-m-Y H:i:s', $model->updated_at),
                'groupOptions' => ['class' => 'table-info text-center']
            ],
        ],
    ]);
    ?>

    <?php
    $coord = new LatLng(['lat' => $model->geo_lt, 'lng' => $model->geo_ln]);
    $map = new Map([
        'center' => $coord,
        'zoom' => 14,
        'width' => '100%',
    ]);

    // Lets add a marker now
    $marker = new Marker([
        'position' => $coord,
        'title' => 'Localização aproximada do acesso',
    ]);

// Provide a shared InfoWindow to the marker
    $marker->attachInfoWindow(
            new InfoWindow([
                'content' => '<p>Este evento foi criado a partir desta localização aproximada</p>'
                    ])
    );

// Add marker to the map
    $map->addOverlay($marker);

    echo $map->display();
    ?>

</div>
