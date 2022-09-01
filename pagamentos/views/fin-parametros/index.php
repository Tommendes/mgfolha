<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\FinParametrosController;
use pagamentos\models\FinParametros;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\FinParametrosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FinParametrosController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="fin-parametros-index">

    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => '95px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['fin-parametros/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => strtolower(FinParametrosController::CLASS_VERB_NAME)]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->financeiro < 3 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['fin-parametros/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => strtolower(FinParametrosController::CLASS_VERB_NAME)]),
                            'class' => 'showModalButton button-as-link',
                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['fin-parametros/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => FinParametrosController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'fin-parametros/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->financeiro < 4 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
//                        $url = Url::home(true) . 'fin-parametros/dpl/' . $model->slug;
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
        'attribute' => 'ano',
        'format' => 'raw',
        'filter' => yii\helpers\ArrayHelper::map(
                pagamentos\models\FinParametros::find()->where([
                    'dominio' => Yii::$app->user->identity->dominio,
                ])->asArray()->groupBy('ano')->orderBy('ano')->all(), 'ano', 'ano'),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 110px;text-align: left;'],
        'value' => function ($model) {
            return $model->ano;
        }
    ];
    $columns[] = [
        'attribute' => 'mes',
        'format' => 'raw',
        'filter' => \common\models\Listas::getMesesAbrev(true),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 90px;text-align: left;'],
        'value' => function ($model) {
            return \common\models\Listas::getMes($model->mes);
        }
    ];
    $columns[] = [
        'attribute' => 'parcela',
        'format' => 'raw',
        'filter' => yii\helpers\ArrayHelper::map(
                pagamentos\models\FinParametros::find()->where([
                    'dominio' => Yii::$app->user->identity->dominio,
                ])->asArray()->groupBy('parcela')->orderBy('parcela')->all(), 'parcela', 'parcela'),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 90px;text-align: left;'],
        'value' => function ($model) {
            return $model->parcela;
        }
    ];
//    $columns[] = [
//        'attribute' => 'ano_informacao',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->ano_informacao;
//        }
//    ];
//    $columns[] = [
//        'attribute' => 'mes_informacao',
//        'format' => 'raw',
//        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
//        'value' => function ($model) {
//            return $model->mes_informacao;
//        }
//    ];
    //$columns[] = [
    //'attribute' => 'parcela_informacao',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->parcela_informacao;
    //}
    //];
    $columns[] = [
        'attribute' => 'descricao',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: left;'],
        'value' => function ($model) {
            return $model->descricao;
        }
    ];
    $columns[] = [
        'attribute' => 'situacao',
        'format' => 'raw',
        'filter' => \common\models\Listas::getSitsParametro(),
        'filterInputOptions' => ['class' => 'form-control', 'prompt' => 'Selecione...'],
        'contentOptions' => ['style' => 'width: 100px;text-align: left;'],
        'value' => function ($model) {
            return common\models\Listas::getSitParametro($model->situacao);
        }
    ];
    $columns[] = [
        'attribute' => 'd_situacao',
        'format' => 'raw',
        'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'dd/mm/aaaa ou parte'],
        'value' => function ($model) {
            return $model->d_situacao;
        }
    ];
    //$columns[] = [
    //'attribute' => 'mensagem',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->mensagem;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'mensagem_aniversario',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->mensagem_aniversario;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'manad_tipofolha',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->manad_tipofolha;
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $ano = Yii::$app->user->identity->per_ano;
    $mes = Yii::$app->user->identity->per_mes;
    $dominio = Yii::$app->user->identity->dominio;
    $parcela_n = FinParametros::find()->where([
                'ano' => $ano,
                'mes' => $mes,
                'dominio' => $dominio,
            ])->max('parcela') + 1;
    $parametro_atual = FinParametros::find()->where([
                'ano' => $ano,
                'mes' => $mes,
                'parcela' => Yii::$app->user->identity->per_parcela,
                'dominio' => $dominio,
            ])->one();
    $parcela_i = $parametro_atual->ano . '/' . $parametro_atual->mes . '/' . $parametro_atual->parcela;
    $parcela_t = $ano . '/' . $mes . '/' . str_pad($parcela_n, 3, '0', STR_PAD_LEFT);
    $msduplicate = Yii::t('yii', 'Confirma a geração da parcela complementar {parc} tendo como parcela de informação a {parc_i}?', [
                'parc' => $parcela_t,
                'parc_i' => $parcela_i,
    ]);
    $url = Url::home(true) . 'fin-parametros/dpl/';

    $buttonCreate = Yii::$app->user->identity->cadastros < 2 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::a(Yii::t('yii', 'Create {modelClass} {add}', [
                        'modelClass' => strtolower(Html::encode(FinParametrosController::CLASS_VERB_NAME_CPL)),
                        'add' => $parcela_t,
                    ]), '#', [
                'title' => Yii::t('yii', 'Create {modelClass} {add}', [
                    'modelClass' => strtolower(Html::encode(FinParametrosController::CLASS_VERB_NAME_CPL)),
                    'add' => $parcela_t,
                ]),
                'onclick' => "       
                    krajeeDialog.prompt({label:'$msduplicate', placeholder:'Digite \"CONFIRMO $parcela_t\"'}, function (result) {
                        if (result == 'CONFIRMO $parcela_t') {
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
                        } else {
                            krajeeDialog.alert('Para concluir a operação digite \"CONFIRMO $parcela_t\" com letras maiúsculas!')
                        }
                    });
                    return false;
                ",
                'class' => 'btn btn-warning'
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
            'heading' => '<h3 class="panel-title"><i class="fa fa-globe"></i> ' . $this->title . '</h3>',
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
