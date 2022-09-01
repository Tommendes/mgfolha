<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinEventosController;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinEventosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinEventosController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>

<?php
$actColumn = [
    'class' => ActionColumn::class,
    'template' => '{view} {update} {delete} {duplicate}',
    'width' => '95px',
    'buttons' => [
        'view' => function ($url, $model) {
            return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                        'value' => Url::to(['fin-eventos/' . $model->slug . '?modal=1&mv=0']),
                        'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinEventosController::CLASS_VERB_NAME]),
                        'class' => 'showModalButton button-as-link',
            ]);
        },
        'update' => function ($url, $model) {
            return Yii::$app->user->identity->financeiro < 3 ? '' :
                    Html::a('<span class="fa fa-pen-square"></span> '
                            , Url::to(['fin-eventos/' . $model->slug . '?modal=0&mv=' . kartik\detail\DetailView::MODE_EDIT])
                            , [
                        'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinEventosController::CLASS_VERB_NAME]),
                        'class' => 'showModalButton button-as-link',
                            ]
            );
//                return Yii::$app->user->identity->financeiro < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
//                            'value' => Url::to(['fin-eventos/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
//                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinEventosController::CLASS_VERB_NAME]),
//                            'class' => 'showModalButton button-as-link',
//                ]);
        },
        'delete' => function ($url, $model) {
            $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
            $urldelete = Url::home(true) . 'fin-eventos/dlt/' . $model->slug;
            $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
            return Yii::$app->user->identity->financeiro < 4 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
                        'title' => Yii::t('yii', 'Delete') . ' item',
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'onclick' => "
                                if (confirm('$msdelete')) {
                                    $.ajax({
                                        url: '$urldelete',
                                        type: 'post',
                                        success: function (response) {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: response['stat'] == true ? 'Sucesso': 'Ops!',
                                                text: response['mess'],
                                                type: response['class'],
                                                styling: 'fontawesome',
                                                animate: {
                                                    animate: true,
                                                    in_class: 'rotateInDownLeft',
                                                    out_class: 'rotateOutUpRight'
                                                }
                                            });
                                            $.pjax.reload({container: '#$idGridPJax'});
                                            if (response['reloadPage'] == true){
                                                javascript:location.reload();
                                            } 
                                        },
//                                                error: function (response) {
//                                                    new PNotify({
//                                                        title: 'Erro',
//                                                        text: response['mess'],
//                                                        type: response['class'], 
//                                                        styling: 'fontawesome',
//                                                        animate: {
//                                                            animate: true,
//                                                            in_class: 'rotateInDownLeft',
//                                                            out_class: 'rotateOutUpRight'
//                                                        }
//                                                    }); 
//                                                }
                                    });
                                    return false;
                                }
                                return false;
                            ",
            ]);
        },
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'fin-eventos/dpl/' . $model->slug;
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

$columns[] = [
    'attribute' => 'id_evento',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: center;'],
    'value' => function ($model) {
        return $model->id_evento;
    }
];
$columns[] = [
    'attribute' => 'evento_nome',
    'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    'value' => function ($model) {
        return $model->evento_nome;
    }
];
$columns[] = [
    'attribute' => 'tipo',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getCDs(),
    'value' => function ($model) {
        return common\models\Listas::getCD($model->tipo);
    }
];

$columns[] = [
    'attribute' => 'consignado',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->consignado);
    }
];

$columns[] = [
    'attribute' => 'consignavel',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->consignavel);
    }
];
$columns[] = [
    'attribute' => 'deduzconsig',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->deduzconsig);
    }
];
$columns[] = [
    'attribute' => 'automatico',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->automatico);
    }
];
$columns[] = [
    'attribute' => 'vinculacao_dirf',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => yii\helpers\ArrayHelper::map(
            \pagamentos\models\FinEventos::find()
                    ->where([
                        'dominio' => Yii::$app->user->identity->dominio,
                    ])
                    ->andWhere('vinculacao_dirf is not null and length(trim(vinculacao_dirf)) > 0')
                    ->asArray()->groupBy('vinculacao_dirf')->orderBy('vinculacao_dirf')->all(), 'vinculacao_dirf', 'vinculacao_dirf'),
    'value' => function ($model) {
        return ($model->vinculacao_dirf);
    }
];
//    $columns[] = [
//        'attribute' => 'i_prioridade',
//        'format' => 'raw',
//        'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
//        'filter' => yii\helpers\ArrayHelper::map(
//                \pagamentos\models\FinEventos::find()
//                        ->where([
//                            'dominio' => Yii::$app->user->identity->dominio,
//                        ])
//                        ->andWhere(['>', 'i_prioridade', '0'])
//                        ->asArray()->orderBy('i_prioridade')->all(), 'i_prioridade', 'i_prioridade'),
//        'value' => function ($model) {
//            return ($model->i_prioridade);
//        }
//    ];
$columns[] = [
    'attribute' => 'fixo',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->fixo);
    }
];
$columns[] = [
    'attribute' => 'sefip',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->sefip);
    }
];
$columns[] = [
    'attribute' => 'rais',
    'format' => 'raw',
    'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
    'filter' => common\models\Listas::getSNs(),
    'value' => function ($model) {
        return common\models\Listas::getSN($model->rais);
    }
];
$columns[] = $actColumn;

$dataProvider->pagination->pageSize = $modal ? 5 : 50;
$buttonCreate = Yii::$app->user->identity->cadastros < 2 ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinEventosController::CLASS_VERB_NAME))
                ]), [
            'id' => 'btn_n_' . Yii::$app->controller->id,
            'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
            'title' => Yii::t('yii', 'Create {modelClass}', [
                'modelClass' => strtolower(Html::encode(FinEventosController::CLASS_VERB_NAME))
            ]),
            'class' => 'showModalButton btn btn-warning'
        ]);
?>

<div class="fin-eventos-index" >
    <!--style="overflow-y: scroll; height: 400px;"-->
    <?php
    $perfectScrollbarHeight = !isset($perfectScrollbarHeight) ? '100%' : $perfectScrollbarHeight;
    $perfectScrollbar = !isset($perfectScrollbar) ? false : $perfectScrollbar;
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
        'floatHeader' => $perfectScrollbar,
        'perfectScrollbar' => $perfectScrollbar,
        'perfectScrollbarOptions' => [
            'position' => 'relative',
            'height' => "$perfectScrollbarHeight",
        ],
        'striped' => true,
        'hover' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . ' ' . $buttonCreate . '</h3>',
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
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#$idGridPJax'});",
                ]) .
                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                    'class' => 'btn btn-secondary',
                    'title' => Yii::t('yii', 'Remover todos os filtros'),
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
