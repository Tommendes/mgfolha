<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinSfuncionalController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinSfuncionalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinSfuncionalController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="fin-sfuncional-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['fin-sfuncional/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => FinSfuncionalController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 3 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-sfuncional/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => FinSfuncionalController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-sfuncional/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinSfuncionalController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
//            'delete' => function ($url, $model) {
//                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
//                $urldelete = Url::home(true) . 'fin-sfuncional/dlt/' . $model->slug;
//                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
//                return Yii::$app->user->identity->financeiro < 4  || !pagamentos\controllers\FinParametrosController::getS() ?                        '' : Html::a('<span class="fa fa-trash"></span>', '#', [
//                            'title' => Yii::t('yii', 'Delete') . ' item',
//                            'aria-label' => Yii::t('yii', 'Delete'),
//                            'onclick' => "
//                                if (confirm('$msdelete')) {
//                                    $.ajax({
//                                        url: '$urldelete',
//                                        type: 'post',
//                                        success: function (response) {
//                                            PNotify.removeAll();
//                                            new PNotify({
//                                                title: response['stat'] == true ? 'Sucesso': 'Ops!',
//                                                text: response['mess'],
//                                                type: response['class'],
//                                                styling: 'fontawesome',
//                                                animate: {
//                                                    animate: true,
//                                                    in_class: 'rotateInDownLeft',
//                                                    out_class: 'rotateOutUpRight'
//                                                }
//                                            });
//                                            $.pjax.reload({container: '#$idGridPJax'});
//                                            if (response['reloadPage'] == true){
//                                                javascript:location.reload();
//                                            } 
//                                        },
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
//                                    });
//                                    return false;
//                                }
//                                return false;
//                            ",
//                ]);
//            },
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'fin-sfuncional/dpl/' . $model->slug;
//                        return Yii::$app->user->identity->financeiro < 2  || !pagamentos\controllers\FinParametrosController::getS() ?                        '' : Html::a(
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
        'attribute' => 'id_cad_servidores',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->id_cad_servidores;
        }
    ];
    $columns[] = [
        'attribute' => 'ano',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->ano;
        }
    ];
    $columns[] = [
        'attribute' => 'mes',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->mes;
        }
    ];
    $columns[] = [
        'attribute' => 'parcela',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->parcela;
        }
    ];
    $columns[] = [
        'attribute' => 'situacao',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->situacao;
        }
    ];
    //$columns[] = [
    //'attribute' => 'situacaofuncional',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->situacaofuncional;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'id_cad_cargos',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->id_cad_cargos;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'id_cad_centros',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->id_cad_centros;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'id_cad_departamentos',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->id_cad_departamentos;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'id_pccs',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->id_pccs;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'desconta_irrf',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->desconta_irrf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'tp_previdencia',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->tp_previdencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'desconta_sindicato',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->desconta_sindicato;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_anuenio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_anuenio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_trienio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_trienio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_quinquenio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_quinquenio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_decenio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_decenio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_salario',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_salario;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'lanca_funcao',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->lanca_funcao;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'n_faltas',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->n_faltas;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'decimo_aniv',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->decimo_aniv;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'n_horaaula',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->n_horaaula;
    //} 
    //];
    //$columns[] = [
    //'attribute' => 'n_adnoturno',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->n_adnoturno;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'n_hextra',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->n_hextra;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'categoria_receita',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->categoria_receita;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'previdencia',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->previdencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'tipobeneficio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->tipobeneficio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'd_beneficio',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->d_beneficio;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'retorno_ocorrencia',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->retorno_ocorrencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'retorno_data',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->retorno_data;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'retorno_valor',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->retorno_valor;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'retorno_documento',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->retorno_documento;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->cadastros < 2 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(FinSfuncionalController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(FinSfuncionalController::CLASS_VERB_NAME))
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
