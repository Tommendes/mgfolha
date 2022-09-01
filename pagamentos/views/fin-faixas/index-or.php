<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinFaixasController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinFaixasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinFaixasController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="fin-faixas-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['fin-faixas/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinFaixasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-faixas/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinFaixasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-faixas/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinFaixasController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'fin-faixas/dlt/' . $model->slug;
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
//                        $url = Url::home(true) . 'fin-faixas/dpl/' . $model->slug;
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
        'attribute' => 'tipo',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->tipo;
        }
    ];
    $columns[] = [
        'attribute' => 'data',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->data;
        }
    ];
    $columns[] = [
        'attribute' => 'faixa',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->faixa;
        }
    ];
    $columns[] = [
        'attribute' => 'v_final1',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->v_final1;
        }
    ];
    $columns[] = [
        'attribute' => 'v_faixa1',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->v_faixa1;
        }
    ];
    //$columns[] = [
    //'attribute' => 'v_deduzir1',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_deduzir1;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_final2',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_final2;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_faixa2',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_faixa2;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_deduzir2',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_deduzir2;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_final3',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_final3;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_faixa3',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_faixa3;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_deduzir3',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_deduzir3;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_final4',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_final4;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_faixa4',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_faixa4;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_deduzir4',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_deduzir4;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_final5',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_final5;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_faixa5',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_faixa5;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'v_deduzir5',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->v_deduzir5;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'deduzir_dependente',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->deduzir_dependente;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'salario_vigente',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->salario_vigente;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'inss_teto',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->inss_teto;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'inss_patronal',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->inss_patronal;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'inss_rat',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->inss_rat;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'inss_fap',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->inss_fap;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'rpps_patronal',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->rpps_patronal;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FinFaixasController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinFaixasController::CLASS_VERB_NAME))
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
                'id' => $idGridPJax = Yii::$app->controller->id . '_grid-pjax',
            ]
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
