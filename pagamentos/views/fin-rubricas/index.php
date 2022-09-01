<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinRubricasController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinRubricasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinRubricasController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;
$servidor = pagamentos\models\CadServidores::find()
                ->where([
                    'dominio' => Yii::$app->user->identity->dominio,
                    'matricula' => $matricula,
                ])->one();

$modal = !isset($modal) ? false : $modal;
?>
<div class="fin-rubricas-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return (Yii::$app->user->identity->financeiro < 1) ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['fin-rubricas/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinRubricasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return (Yii::$app->user->identity->financeiro < 3 || $model->getFinEvento()->automatico == 1) || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-rubricas/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinRubricasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-rubricas/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinRubricasController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'fin-rubricas/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return (Yii::$app->user->identity->financeiro < 4 || $model->getFinEvento()->automatico == 1) || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
//                        $url = Url::home(true) . 'fin-rubricas/dpl/' . $model->slug;
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
//    $columns[] = [
//        'attribute' => 'id_cad_servidores',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->id_cad_servidores;
//        }
//    ];
    $columns[] = [
        'attribute' => 'id_fin_eventos',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            $evento = $model->getFinEvento();
            return '(' . $evento->id_evento .'[ID: ' . $evento->id . ']'. ') ' . $evento->evento_nome;
        }
    ];
//    $columns[] = [
//        'attribute' => 'ano',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->ano;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'mes',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->mes;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'parcela',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->parcela;
//        }
//    ];
    $columns[] = [
        'attribute' => 'referencia',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->referencia;
        }
    ];
    $columns[] = [
        'attribute' => 'valor_base',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return 'R$' . number_format($model->valor_base, 2, ',', '.');
        }
    ];
    $columns[] = [
        'attribute' => 'valor',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return 'R$' . number_format($model->valor, 2, ',', '.');
        }
    ];
    $columns[] = [
        'attribute' => 'valor_patronal',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return 'R$' . number_format($model->valor_patronal, 2, ',', '.');
        }
    ];
    $columns[] = [
        'attribute' => 'valor_maternidade',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return 'R$' . number_format($model->valor_maternidade, 2, ',', '.');
        }
    ];
    $columns[] = [
        'attribute' => 'prazo',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->prazo;
        }
    ];
    $columns[] = [
        'attribute' => 'prazot',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->prazot;
        }
    ];
    $columns[] = [
        'attribute' => 'valor_desconto',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return 'R$' . number_format($model->valor_desconto, 2, ',', '.');
        }
    ];
    $columns[] = [
        'attribute' => 'valor_percentual',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return number_format($model->valor_percentual, 2, ',', '.') . '%';
        }
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 || !pagamentos\controllers\FinParametrosController::getS() || $servidor->getFalecimento() !== null ? '' : Html::button(Yii::t('yii', 'Nova {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FinRubricasController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal . '&cp=' . $servidor->id]),
                'title' => Yii::t('yii', 'Nova {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinRubricasController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $modal ? '' : $searchModel,
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
            'heading' => $buttonCreate, //'<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . ' ' . $buttonCreate . '</h3>',
            'type' => 'primary',
            'before' => '',
            'after' => '', //Html::a('<i class="fa fa-repeat"></i> Resetar Grid', ['index'], ['class' => 'btn btn-info']),
            'footer' => $dataProvider->totalCount > $dataProvider->pagination->pageSize ? '' : false,
        ],
        'toolbar' => null,
//        !$modal ? [
//            [
//                'content' => 
//                $buttonCreate 
//                Html::button('<i class="fa fa-sync-alt"></i>', [
//                    'class' => 'btn btn-secondary',
//                    'title' => Yii::t('yii', 'Reset Grid'),
//                    'onclick' => "$.pjax.reload({container: '#$idGridPJax'});",
//                ]) . 
//                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
//                    'class' => 'btn btn-secondary',
//                    'title' => Yii::t('yii', 'Remover todos os filtros'),
//                ]),
//            ],
//            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
//            '{toggleData}',
//                ] : null,
    ]);
    ?>
</div>
