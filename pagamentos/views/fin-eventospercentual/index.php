<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinEventospercentualController;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinEventospercentualSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$id_fin_eventos = isset($id_fin_eventos) && !empty($id_fin_eventos) ? $id_fin_eventos : null;
$modal = !isset($modal) ? false : $modal;
$perfectScrollbarHeight = isset($perfectScrollbarHeight) && $perfectScrollbarHeight != null ? $perfectScrollbarHeight : '520px';
$perfectScrollbar = !isset($perfectScrollbar) ? false : $perfectScrollbar;
if (!$modal && !$perfectScrollbar) {
    $this->title = FinEventospercentualController::CLASS_VERB_NAME_PL;
    $this->params['breadcrumbs'][] = $this->title;
}
$btn_reload = 'btn_reload_' . Yii::$app->security->generateRandomString(8);
?>
<div class="fin-eventospercentual-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{update} {delete}',
//        'width' => '95px',
        'buttons' => [
//            'view' => function ($url, $model) {
//                return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
//                            'value' => Url::to(['fin-eventospercentual/' . $model->slug . '?modal=1&mv=0']),
//                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinEventospercentualController::CLASS_VERB_NAME]),
//                            'class' => 'showModalButton button-as-link',
//                ]);
//            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-eventospercentual/' . $model->slug . '?modal=1&mv=' . DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinEventospercentualController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-eventospercentual/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinEventospercentualController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'fin-eventospercentual/dlt/' . $model->slug;
                return Yii::$app->user->identity->financeiro < 4 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
                            'title' => Yii::t('yii', 'Delete') . ' item',
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'onclick' => "
                                krajeeDialog.prompt({label:'$msdelete', placeholder:'Digite \"CONFIRMO\"'}, function (result) { 
                                    if (result == 'CONFIRMO') {  
                                        $.ajax({
                                            url: '$urldelete',
                                            type: 'post',
                                            success: function (response) {
                                                retorno_del = response;
                                            },
                                            error: function (response) {
                                                retorno_del = response;
                                            }
                                        });
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Sucesso!',
                                            text: retorno_del.responseText,
                                            type: 'info',
                                            styling: 'fontawesome',
                                            animate: {
                                                animate: true,
                                                in_class: 'rotateInDownLeft',
                                                out_class: 'rotateOutUpRight'
                                            }
                                        });
                                        $.pjax.reload({container: '#fin_eventospercentual_grid_pjax'});
                                        return false;                          
                                    } else {
                                        krajeeDialog.alert('Para concluir a operação digite \"CONFIRMO\" com letras maiúsculas!')
                                    }
                                });
                            ",
                ]);
            },
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'fin-eventospercentual/dpl/' . $model->slug;
//                        return Yii::$app->user->identity->financeiro < 2 ? '' : Html::a(
//                                        '<span class="fa fa-clone"></span>', '#', [
//                                    'title' => Yii::t('yii', 'Duplicate') . ' item',
//                                    'aria-label' => Yii::t('yii', 'Duplicate'),
//                                    'onclick' => "
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
//                        ]);
//                    },
        ],
    ];

    //    $columns[] = ['class' => 'kartik\grid\SerialColumn'];
    if (!isset($cl) || empty($cl) || (isset($cl) && is_array($cl) && in_array('id_fin_eventos', $cl))) {
        $columns[] = [
            'attribute' => 'id_fin_eventos',
            'format' => 'raw',
            //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
            'value' => function ($model) {
                return $model->id_fin_eventos;
            }
        ];
    }
    if (!isset($cl) || empty($cl) || (isset($cl) && is_array($cl) && in_array('id_eventos_percentual', $cl))) {
        $columns[] = [
            'attribute' => 'id_eventos_percentual',
            'format' => 'raw',
            //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
            'value' => function ($model) {
                return $model->id_eventos_percentual;
            }
        ];
    }
    if (!isset($cl) || empty($cl) || (isset($cl) && is_array($cl) && in_array('data', $cl))) {
        $columns[] = [
            'attribute' => 'data',
            'format' => 'raw',
            //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
            'value' => function ($model) {
                return $model->data;
            }
        ];
    }
    if (!isset($cl) || empty($cl) || (isset($cl) && is_array($cl) && in_array('percentual', $cl))) {
        $columns[] = [
            'attribute' => 'percentual',
            'format' => 'raw',
            'contentOptions' => ['style' => 'text-align: right;'],
            'value' => function ($model) {
                return $model->percentual;
            }
        ];
    }
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = (Yii::$app->user->identity->financeiro < 2 || is_null($id_fin_eventos) || $mv !== DetailView::MODE_EDIT) ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FinEventospercentualController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_fin_eventospercentual',
                'value' => Url::to(['/fin-eventospercentual/c?modal=' . $modal . '&cp=' . $id_fin_eventos]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinEventospercentualController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => 'fin_eventospercentual_grid_pjax',
            ]
        ],
        'floatHeader' => isset($perfectScrollbarHeight) && $perfectScrollbarHeight != null,
        'perfectScrollbar' => isset($perfectScrollbarHeight) && $perfectScrollbarHeight != null,
//        'perfectScrollbarOptions' => [
//            'position' => 'relative',
//            'height' => "$perfectScrollbarHeight",
//        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h' . ($perfectScrollbar ? '6' : '3') . ' class="panel-title"><i class="fa fa-globe"></i> ' . FinEventospercentualController::CLASS_VERB_NAME_PL . ($perfectScrollbar ? '' : ' ' . $buttonCreate) . '</h' . ($perfectScrollbar ? '6' : '3') . '>',
            'type' => 'primary',
            'before' => '',
            'after' => '', //Html::a('<i class="fa fa-repeat"></i> Resetar Grid', ['index'], ['class' => 'btn btn-info']),
            'footer' => $dataProvider->totalCount > $dataProvider->pagination->pageSize ? '' : false,
        ],
        'toolbar' => !$modal ? [
            [
                'content' =>
                $buttonCreate .
                Html::button('<i class="fa fa-sync-alt"></i>', [
                    'class' => 'btn btn-secondary',
                    'id' => $btn_reload,
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#fin_eventospercentual_grid_pjax'});",
                ]) .
                (!$perfectScrollbar ? Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                            'class' => 'btn btn-secondary',
                            'title' => Yii::t('yii', 'Remover todos os filtros'),
                        ]) : ''),
            ],
            Yii::$app->user->identity->gestor >= 1 && !$perfectScrollbar ? '{export}' : '',
            !$perfectScrollbar ? '{toggleData}' : '',
                ] : null,
    ]);
    ?>
</div>
