<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\CadSdependentesController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\models\CadSdependentes;
use pagamentos\controllers\AppController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadSdependentesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$servidor = CadSdependentes::getCadServidor($id_cad_servidores);
$this->title = CadSdependentesController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="cad-sdependentes-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete}', // {duplicate}
        'width' => Yii::$app->user->identity->base_servico == 'pagamentos' ? '95px' : '30px',
        'buttons' => [
            'view' => function ($url, $model) {
                $baseServico = Yii::$app->user->identity->base_servico;
                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(["../$baseServico/cad-sdependentes/" . $model->slug, 'modal' => '1', 'mv' => '0', 'reloadOnClose' => true,]),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadServidoresController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 3 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['cad-sdependentes/' . $model->slug . '?modal=1&mv=edit']),
                            'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => CadSdependentesController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link'
                        ]) : '';
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-sdependentes/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 4 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::a('<span class="fa fa-trash"></span>', '#', [
                            'title' => Yii::t('yii', 'Delete') . ' item',
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'onclick' => "
                                krajeeDialog.confirm('$msdelete', function (result) {
                                    if (result) {
                                        $.ajax({
                                            url: '$urldelete',
                                            type: 'post',
                                            success: function (response) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: response['stat'] == true ? 'Sucesso' : 'Ops!',
                                                    text: response['mess'],
                                                    type: response['class'],
                                                    styling: 'fontawesome',
                                                    animate: {
                                                        animate: true,
                                                        in_class: 'rotateInDownLeft',
                                                        out_class: 'rotateOutUpRight'
                                                    }
                                                });
                                                $('#closeAutoModalHeader').click();
                                            },
                                        });
//                                        return false;
                                    }
                                });
                            ",
                        ]) : '';
            },
//            'duplicate' => function ($url, $model) {
//                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                $url = Url::home(true) . 'cad-sdependentes/dpl/' . $model->slug;
//                return Yii::$app->user->identity->cadastros < 2 ? '' : Html::a(
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
//                            $columns[] = [
//                    'attribute' => 'id',
//                    //'format' => ['decimal', 2],
//                    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//                    'value' => function ($model) {
//                        return $model->id;
//                    }
//                ];
//                                                $columns[] = [
//                    'attribute' => 'slug',
//                    //'format' => ['decimal', 2],
//                    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//                    'value' => function ($model) {
//                        return $model->slug;
//                    }
//                ];
//                                                $columns[] = [
//                    'attribute' => 'status',
//                    //'format' => ['decimal', 2],
//                    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//                    'value' => function ($model) {
//                        return $model->status;
//                    }
//                ];
//                                                $columns[] = [
//                    'attribute' => 'dominio',
//                    //'format' => ['decimal', 2],
//                    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//                    'value' => function ($model) {
//                        return $model->dominio;
//                    }
//                ];
//                                                $columns[] = [
//                    'attribute' => 'evento',
//                    //'format' => ['decimal', 2],
//                    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//                    'value' => function ($model) {
//                        return $model->evento;
//                    }
//                ];
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
    //$columns[] = [
    //'attribute' => 'id_cad_servidores',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->id_cad_servidores;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'matricula',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->matricula;
    //}
    //];
    $columns[] = [
        'attribute' => 'nome',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->nome;
        }
    ];
    $columns[] = [
        'attribute' => 'tipo',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->tipo;
        }
    ];
    //$columns[] = [
    //'attribute' => 'permanente',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->permanente;
    //}
    //];
    $columns[] = [
        'attribute' => 'nascimento_d',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->nascimento_d;
        }
    ];
    //$columns[] = [
    //'attribute' => 'inss_prz',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->inss_prz;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'irrf_prz',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->irrf_prz;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'rpps_prz',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->rpps_prz;
    //}
    //];
    $columns[] = [
        'attribute' => 'rg',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->rg;
        }
    ];
    //$columns[] = [
    //'attribute' => 'rg_emissor',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->rg_emissor;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'rg_uf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->rg_uf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'rg_d',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->rg_d;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'certidao',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->certidao;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'livro',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->livro;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'folha',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->folha;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'certidao_d',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->certidao_d;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'carteira_vacinacao',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->carteira_vacinacao;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'historico_escolar',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->historico_escolar;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'plano_saude',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->plano_saude;
    //}
    //];
    $columns[] = [
        'attribute' => 'cpf',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return \pagamentos\controllers\AppController::setCpfCnpjMask($model->cpf);
        }
    ];
    //$columns[] = [
    //'attribute' => 'relacao_dependecia',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->relacao_dependecia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'pensionista',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->pensionista;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'irpf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->irpf; 
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 2 && pagamentos\controllers\FinParametrosController::getS() && $servidor->getFalecimento() == null ?
            Html::button(Yii::t('yii', 'Novo {modelClass}', [
                        'modelClass' => strtolower(Html::encode(CadSdependentesController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal . '&id_cad_servidores=' . $id_cad_servidores]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(CadSdependentesController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
            ]) : '';

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => !$modal ? $searchModel : null,
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
