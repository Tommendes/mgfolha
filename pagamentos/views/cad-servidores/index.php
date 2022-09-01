<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\CadServidoresController;
use common\models\Listas;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadServidoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CadServidoresController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="cad-servidores-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => Yii::$app->user->identity->base_servico == 'pagamentos' ? '110px' : '30px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->cadastros >= 1 ?
                        Html::a('<span class="fa fa-eye"></span> ', '#', [
//                            'value' => Url::to(['orgao/' . $model->slug . '?modal=1&mv=edit']),
                            'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => CadServidoresController::CLASS_VERB_NAME]),
                            'class' => 'button-as-link',
                            'onclick' => 'window.location.href = "' . Url::to(['cad-servidores/' . $model->slug]) . '";'
                        ]) : '';
//                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
//                            'value' => Url::to(['cad-servidores/' . $model->slug . '?modal=1&mv=0']),
//                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadServidoresController::CLASS_VERB_NAME]),
//                            'class' => 'showModalButton button-as-link',
//                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 3 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::a('<span class="fa fa-pen-square"></span> ', Url::to(['cad-servidores/' . $model->slug . '?mv=edit']), [
//                            'value' => Url::to(['orgao/' . $model->slug . '?modal=1&mv=edit']),
                            'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => CadServidoresController::CLASS_VERB_NAME]),
                            'class' => 'button-as-link',
//                            'onclick' => 'javascript:location.reload();'
                        ]) : '';
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-servidores/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 4 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::a('<span class="fa fa-trash"></span>', '#', [
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
                        ]) : '';
            },
            'duplicate' => function ($url, $model) {
                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
                $url = Url::home(true) . 'cad-servidores/dpl/' . $model->slug;
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 2 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::a(
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
                        ]) : '';
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
        'attribute' => 'matricula',
//'format' => ['decimal', 2],
        'contentOptions' => ['style' => 'width: 120px;'],
        'value' => function ($model) {
            return $model->matricula;
        }
    ];
    $columns[] = [
        'attribute' => 'nome',
//'format' => ['decimal', 2],
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->nome;
        }
    ];
    $columns[] = [
        'attribute' => 'cpf',
        'contentOptions' => ['style' => 'width: 160px;text-align: right;'],
        'value' => function ($model) {
            return \pagamentos\controllers\AppController::setCpfCnpjMask($model->cpf);
        }
    ];
    $columns[] = [
        'attribute' => 'rg',
//'format' => ['decimal', 2],
        'contentOptions' => ['style' => 'width: 150px;text-align: right;'],
        'value' => function ($model) {
            return $model->rg;
        }
    ];
    // if (Yii::$app->user->identity->administrador >= 2) {
        $columns[] = [
            'attribute' => 'situacao',
            'contentOptions' => ['style' => 'width: 100px;'],
            'filter' => Listas::getSituacoesCadastrais(),
            'value' => function ($model) {
                return Listas::getSituacaoCadastral($model->getFinSfuncional()->situacao);
            }
        ];
        $columns[] = [
            'attribute' => 'situacaoFuncional',
            'contentOptions' => ['style' => 'width: 100px;'],
            'filter' => Listas::getSituacoesFuncionais(),
            'value' => function ($model) {
                return Listas::getSituacaoFuncional($model->getFinSfuncional()->situacaofuncional);
            }
        ];
    // }
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
    //'attribute' => 'pispasep',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->pispasep;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'pispasep_d',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->pispasep_d;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'titulo',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->titulo;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'titulosecao',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->titulosecao;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'titulozona',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->titulozona;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'ctps',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->ctps;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'ctps_serie',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->ctps_serie;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'ctps_uf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->ctps_uf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'ctps_d',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->ctps_d;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'nascimento_d',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->nascimento_d;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'pai',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->pai;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'mae',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->mae;
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
    //$columns[] = [
    //'attribute' => 'naturalidade',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->naturalidade;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'naturalidade_uf',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->naturalidade_uf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'telefone',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->telefone;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'celular',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->celular;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'email:email',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->email;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'idbanco',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->idbanco;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'banco_agencia',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->banco_agencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'banco_agencia_digito',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->banco_agencia_digito;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'banco_conta',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->banco_conta;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'banco_conta_digito',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->banco_conta_digito;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'banco_operacao',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->banco_operacao;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'nacionalidade',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->nacionalidade;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'sexo',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->sexo;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'raca',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->raca;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'estado_civil',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->estado_civil;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'tipodeficiencia',
    //'format' => ['decimal', 2],
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->tipodeficiencia;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'd_admissao',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->d_admissao;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->base_servico === 'pagamentos' && Yii::$app->user->identity->cadastros >= 2 && pagamentos\controllers\FinParametrosController::getS() ? Html::button(Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(CadServidoresController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=1']),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(CadServidoresController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning'
            ]) : '';

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
//            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
