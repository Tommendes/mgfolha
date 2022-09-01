<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use frontend\controllers\SisEventsController;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SisEventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = SisEventsController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="eventos-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
//        'template' => '{update} {delete} {duplicate}',
        'template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model) {
                return Html::button('<span class="fa fa-eye"></span>', [
                            'value' => Url::to(['eventos/' . $model->slug]),
                            'title' => Yii::t('yii', 'Ver evento'),
                            'class' => 'showModalButton button-as-link'
                ]);
            },
//            'delete' => function ($url, $model) {
//                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
//                $urldelete = Url::home(true) . 'eventos/dlt/' . $model->slug;
//                return Html::a('<span class="fa fa-trash"></span>', '#', [
//                            'title' => Yii::t('yii', 'Delete') . ' item',
//                            'aria-label' => Yii::t('yii', 'Delete'),
//                            'onclick' => "
//                                        if (confirm('$msdelete')) {
//                                            $.ajax({
//                                                url: '$urldelete',
//                                                type: 'post',
//                                                success: function (response) {
//                                                    PNotify.removeAll();
//                                                    new PNotify({
//                                                        title: response['stat'] == true ? 'Sucesso': 'Ops!',
//                                                        text: response['mess'],
//                                                        type: response['class'],
//                                                        styling: 'fontawesome',
//                                                        animate: {
//                                                            animate: true,
//                                                            in_class: 'rotateInDownLeft',
//                                                            out_class: 'rotateOutUpRight'
//                                                        }
//                                                    });
//                                                    if (response['reloadPage'] == true){
//                                                        javascript:location.reload();
//                                                    } 
//                                                },
////                                                error: function (response) {
////                                                    new PNotify({
////                                                        title: 'Erro',
////                                                        text: response['mess'],
////                                                        type: response['class'], 
////                                                        styling: 'fontawesome',
////                                                        animate: {
////                                                            animate: true,
////                                                            in_class: 'rotateInDownLeft',
////                                                            out_class: 'rotateOutUpRight'
////                                                        }
////                                                    });
////                                                }
//                                            });
//                                            return false;
//                                        }
//                                        return false;
//                                    ",
//                ]);
//            },
//            'duplicate' => function ($url, $model) {
//                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                $url = Url::home(true) . 'eventos/dpl/' . $model->slug;
//                return Html::a(
//                                '<span class="fa fa-clone"></span>', '#', [
//                            'title' => Yii::t('yii', 'Duplicate') . ' item',
//                            'aria-label' => Yii::t('yii', 'Duplicate'),
//                            'onclick' => "
//                                            krajeeDialog.confirm('$msduplicate', function(out){
//                                                    if(out) {
//                                                        $.ajax('$url', {
//                                                        type: 'POST'
//                                                    }).done(function(data) {
//                                                        new PNotify({ 
//                                                            title: 'Sucesso', 
//                                                            text: data,
//                                                            type: 'success',
//                                                            styling: 'fontawesome',
//                                                            animate: {
//                                                                animate: true,
//                                                                in_class: 'rotateInDownLeft',
//                                                                out_class: 'rotateOutUpRight'
//                                                            }
//                                                        });
//                                                    });
//                                                }
//                                            })
//                                        ",
//                ]);
//            },
        ],
    ];

    //    $columns[] = ['class' => 'kartik\grid\SerialColumn'];
//    $columns[] = [
//        'attribute' => 'id',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->id;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'slug',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->slug;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'status',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->status;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'dominio',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->dominio;
//        }
//    ];
    $columns[] = [
        'attribute' => 'evento',
        'format' => ['raw'],
        'contentOptions' => ['style' => 'width: 60%'],
        'value' => function ($model) {
            return $model->evento;
        }
    ];
    $columns[] = [
        'attribute' => 'classevento',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'filter' => common\models\Listas::getEventoDescris(),
        'value' => function ($model) {
            return SisEventsController::getEventoDescri($model->classevento);
        }
    ];
    if (!isset(Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd']) || empty(Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd'])) {
        $columns[] = [
            'attribute' => 'tabela_bd',
            'value' => function ($model) {
                return $model->tabela_bd;
            }
        ];
    }
    if (!isset(Yii::$app->request->queryParams['SisEventsSearch']['id_registro']) || empty(Yii::$app->request->queryParams['SisEventsSearch']['id_registro'])) {
        $columns[] = [
            'attribute' => 'id_registro',
            'value' => function ($model) {
                return $model->id_registro;
            }
        ];
    }
    //$columns[] = [
    //'attribute' => 'ip',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->ip;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'geo_lt',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->geo_lt;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'geo_ln',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->geo_ln;
    //}
    //];
    $columns[] = [
        'attribute' => 'id_user',
        'value' => function ($model) {
            return \common\models\User::findOne($model->id_user)->username;
        }
    ];
    //$columns[] = [
    //'attribute' => 'username',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->username;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'data_registro',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->data_registro;
    //}
    //];
    $columns[] = [
        'attribute' => 'created_at',
        'value' => function ($model) {
            return date('d-m-Y G:i:s', $model->created_at);
        },
        'contentOptions' => ['style' => 'width: 240px'],
        'hAlign' => 'center',
//        'vAlign' => 'middle',
//        'format' => 'date',
//        'options' => [
//            'format' => 'YYYY-MM-DD',
//        ],
        'filterType' => GridView::FILTER_DATE_RANGE,
        'filterWidgetOptions' => (
        [
            'attribute' => 'created_at',
            'presetDropdown' => true,
            'convertFormat' => false,
            'pluginOptions' => [
                'separator' => ' - ',
                'format' => 'YYYY-MM-DD',
                'locale' => [
                    'format' => 'YYYY-MM-DD'
                ],
            ],
            'pluginEvents' => [
                "apply.daterangepicker" => "function() { apply_filter('created_at') }",
            ],
        ])
    ];
    //$columns[] = [
    //'attribute' => 'updated_at',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->updated_at;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
//    $buttonCreate = Html::button(Yii::t('yii', 'Createa {modelClass}', [
//                        'modelClass' => strtolower(Html::encode(SisEventsController::CLASS_VERB_NAME_PL))
//                    ]), [
//                'id' => 'btn_n_' . Yii::$app->controller->id,
//                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
//                'title' => Yii::t('yii', 'Createa {modelClass}', [
//                    'modelClass' => strtolower(Html::encode(SisEventsController::CLASS_VERB_NAME_PL))
//                ]),
//                'class' => 'showModalButton btn btn-warning'
//    ]);
    $heading_url = '';
    if (isset(Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd']) && !empty(Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd'])) {
        $heading = Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd'];
        if (isset(Yii::$app->request->queryParams['SisEventsSearch']['id_registro']) && !empty(Yii::$app->request->queryParams['SisEventsSearch']['id_registro'])) {
            $heading_id_registro = Yii::$app->request->queryParams['SisEventsSearch']['id_registro'];
            $heading_url = str_replace('_', '-', str_replace('', '', Yii::$app->request->queryParams['SisEventsSearch']['tabela_bd'])) . '/' . Yii::$app->request->queryParams['SisEventsSearch']['id_registro'];
            $heading_url = ' em ' . $heading . '&nbsp;' . Html::a('registro ID ' . $heading_id_registro, $url = Url::home(true) . $heading_url, ['title' => 'Clique para seguir para o registro caso ele ainda exista e pertença à folha atual.', 'class' => 'text-white', 'data-toggle' => "tooltip",
                        'data-placement' => "bottom", 'onclick' => "window.replace.href='$url'"/*'javascript:location.reload();'*/]);
        }
    }
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => $idGridPJax = Yii::$app->controller->id . '_grid-pjax',
            ]
        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . '&nbsp;' . $heading_url . '</h3>',
            'type' => 'primary',
            'before' => '',
            'after' => '', //Html::a('<i class="fa fa-repeat"></i> Resetar Grid', ['index'], ['class' => 'btn btn-info']),
            'footer' => $dataProvider->totalCount > $dataProvider->pagination->pageSize ? '' : false,
        ],
        'toolbar' => !$modal ? [
            [
                'content' =>
                Html::button('<i class="fa fa-sync-alt"></i>', [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#$idGridPJax'});",
                ]) .
                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Remover todos os filtros'),
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
