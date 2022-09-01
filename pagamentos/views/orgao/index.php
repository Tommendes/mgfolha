<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\OrgaoController;
use frontend\controllers\SisParamsController;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrgaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = OrgaoController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
$idGridPJax = Yii::$app->controller->id . '_grid-pjax';
?>
<div class="orgao-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{update} {delete} {duplicate}',
        'width' => '90px',
        'buttons' => [
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::a('<span class="fa fa-pen-square"></span> ', Url::to(['orgao/' . $model->slug . '?modal=1&mv=edit']), [
//                            'value' => Url::to(['orgao/' . $model->slug . '?modal=1&mv=edit']),
                            'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => OrgaoController::CLASS_VERB_NAME]),
                            'class' => 'button-as-link',
                            'onclick' => 'javascript:location.reload();'
                ]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'orgao/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
            'duplicate' => function ($url, $model) {
                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
                $url = Url::home(true) . 'orgao/dpl/' . $model->slug;
                return Yii::$app->user->identity->administrador < 1 ? '' : Html::a(
                                '<span class="fa fa-clone"></span>', '#', [
                            'title' => Yii::t('yii', 'Duplicate') . ' item',
                            'aria-label' => Yii::t('yii', 'Duplicate'),
                            'onclick' => "
                                            krajeeDialog.confirm('$msduplicate', function(out){
                                                    if(out) {
                                                        $.ajax('$url', {
                                                        type: 'POST'
                                                    }).done(function(data) {
                                                        new PNotify({ 
                                                            title: 'Sucesso', 
                                                            text: data,
                                                            type: 'success',
                                                            styling: 'fontawesome',
                                                            animate: {
                                                                animate: true,
                                                                in_class: 'rotateInDownLeft',
                                                                out_class: 'rotateOutUpRight'
                                                            }
                                                        });
                                                    });
                                                }
                                            })
                                        ",
                ]);
            },
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
//    $columns[] = [
//        'attribute' => 'evento',
//        //'format' => ['decimal', 2],
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->evento;
//        }
//    ];
    //$columns[] = [
    //'attribute' => 'created_at',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->created_at;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'updated_at',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->updated_at;
    //}
    //];
    $columns[] = [
        'attribute' => 'orgao',
//        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->orgao;
        }
    ];
    $columns[] = [
        'attribute' => 'cnpj',
//        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->cnpj;
        }
    ];
    //$columns[] = [
    //'attribute' => 'url_logo:url',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->url_logo;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cep',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cep;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'logradouro',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->logradouro;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'numero',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->numero;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'complemento',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->complemento;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'bairro',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->bairro;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cidade',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cidade;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'uf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->uf;
    //}
    //];
    $columns[] = [
        'attribute' => 'email',
        'format' => ['email'],
//        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->email;
        }
    ];
    $columns[] = [
        'attribute' => 'telefone',
//        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->telefone;
        }
    ];
    //$columns[] = [
    //'attribute' => 'codigo_fpas',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->codigo_fpas;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'codigo_gps',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->codigo_gps;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'codigo_cnae',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->codigo_cnae;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'codigo_ibge',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->codigo_ibge;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'codigo_fgts',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->codigo_fgts;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'mes_descsindical',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->mes_descsindical;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cpf_responsavel_dirf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cpf_responsavel_dirf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'nome_responsavel_dirf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->nome_responsavel_dirf;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = (Yii::$app->user->identity->administrador >= 1 ? Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(OrgaoController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(ParamsController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
            ]) : '' );

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $modal ? null : $searchModel,
        'columns' => $columns,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'options' => [
                'id' => $idGridPJax,
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
