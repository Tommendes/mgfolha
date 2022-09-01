<?php

use kartik\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use pagamentos\models\Suporte;
use common\models\SisParams;
use pagamentos\controllers\SuporteController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\SisSuporteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('yii', 'Sis Suportes');
//$this->params['breadcrumbs'][] = $this->title;
$this->title = SuporteController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = SuporteController::CLASS_VERB_NAME;
$status = ArrayHelper::map(Params::find()
                        ->where([
                            'dominio' => 'lynkos',
                            'grupo' => 'sis_status'
                        ])
                        ->orderBy('parametro')
                        ->all(), 'parametro', 'label');
?>
<div class="<?= SuporteController::CLASS_VERB_NAME ?>-index">

    <h1 class="text-center"><?=
        Html::encode(SuporteController::CLASS_VERB_NAME) . ' ' . Html::button(Html::icon('search'), [
            'class' => 'btn btn-warning',
            'title' => Yii::t('yii', 'Show advanced search'),
            'aria-label' => Yii::t('yii', 'Show advanced search'),
            'id' => 'show-search-btn',
        ]) . Html::button(Html::icon('chevron-up'), [
            'class' => 'btn btn-primary hide',
            'title' => Yii::t('yii', 'Hide advanced search'),
            'aria-label' => Yii::t('yii', 'Hide advanced search'),
            'id' => 'hide-search-btn',
        ])
        ?></h1>
    <div id="search_form" class="hide">
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <p>        
        <?= Html::a(Yii::t('yii', 'Create {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 20px'],
            ],
//            'id',
//            'dominio',
            [
                'attribute' => 'dominio',
                'format' => 'raw',
                'filter' => ArrayHelper::map($searchModel->getDominio(), 'id', 'dominio'),
                'value' => function ($data) {
                    return $data->getDominio($data->dominio);
                },
                'contentOptions' => ['style' => 'width: 200px;'],
            ],
            [
                'attribute' => 'grupo',
                'format' => 'raw',
                'filter' => ArrayHelper::map($searchModel->getGrupo(), 'id', 'label'),
                'value' => function ($data) {
                    return $data->getGrupo($data->grupo);
                },
                'contentOptions' => ['style' => 'width: 200px;'],
            ],
//            'slug',
            'descricao',
//            'texto:ntext',
            [
                'attribute' => 'status',
                'filter' => $status,
                'value' => function ($data) {
                    return SisParams::getParamsLabel($data->status, 'sis_status');
                },
                'label' => 'Status',
                'contentOptions' => ['style' => 'width: 140px;'],
            ],
            // 'evento',
            // 'created_at',
            // 'updated_at',
            //['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 85px'],
//                                              /*'template' => $searchModel->status_params >= 10 ? '{view}' : '{view}{update}{delete}',*/
                'template' => '{view} {update} {duplicate} {delete}', // {print}
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::home(true) . 'suporte/' . $model->slug, [
                                    'title' => Yii::t('yii', 'View'),
                                    'aria-label' => Yii::t('yii', 'View'),
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Suporte::STATUS_CANCELADO === $model->status ? '' : Html::a(
                                        '<span class="glyphicon glyphicon-pencil"></span>', Url::home(true) . 'suporte/' . $model->slug . '?mv=edit', [
                                    'title' => Yii::t('yii', 'Update'),
                                    'aria-label' => Yii::t('yii', 'Update'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Suporte::STATUS_CANCELADO === $model->status ? '' : Html::a(
                                        '<span class="glyphicon glyphicon-trash"></span>', Url::home(true) . 'suporte/delete/' . $model->slug, [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data' => [
                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                    'duplicate' => function ($url, $model) {
                        return Html::a(
                                        '<span class="glyphicon glyphicon-duplicate"></span>', Url::home(true) . 'suporte/duplicate/' . $model->slug, [
                                    'title' => Yii::t('yii', 'Duplicate'),
                                    'aria-label' => Yii::t('yii', 'Duplicate'),
                                    'data' => [
                                        'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
                                        'method' => 'post',
                                    ],
                        ]);
                    },
//                    'print' => function ($url, $model) {
//                        return Html::a(
//                                        '<span class="glyphicon glyphicon-print"></span>', Url::home(true) . 'suporte/imprimir/' . $model->id, [
//                                    'title' => Yii::t('yii', 'Print'),
//                                    'aria-label' => Yii::t('yii', 'Print'),
//                                    'data' => [
//                                        'confirm' => Yii::t('yii', 'Confirm printing this item?'),
//                                        'method' => 'post',
//                                    ],
//                                    'target' => '_blank',
//                        ]);
//                    },
                ],
            ],
        ],
    ]);
    ?>
    <!--</div>-->

    <?php
    $js = <<< JS
    $(document).ready(function () {
        $('#show-search-btn').click(function () {
            $("#search_form").removeClass("hide");
            $("#hide-search-btn").removeClass("hide");
            $("#search_form").show("slow");
            $("#hide-search-btn").show();
            $("#show-search-btn").hide();
        });
        $('#hide-search-btn').click(function () {
            $("#search_form").hide("slow");
            $("#show-search-btn").show();
            $("#hide-search-btn").hide();
        });
    });
JS;
    $this->registerJs($js);
    ?>