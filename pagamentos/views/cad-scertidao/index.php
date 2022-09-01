<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\CadScertidaoController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadScertidaoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$servidor = CadScertidaoController::getCadServidor($id_cad_servidores);
$this->title = CadScertidaoController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="cad-scertidao-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate}',
        'width' => Yii::$app->user->identity->base_servico == 'pagamentos' ? '110px' : '30px',
        'buttons' => [
            'view' => function ($url, $model) {
                $baseServico = Yii::$app->user->identity->base_servico;
                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(["../$baseServico/cad-scertidao/" . $model->slug, 'modal' => '1', 'mv' => '0', 'reloadOnClose' => true,]),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadScertidaoController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 3 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['cad-scertidao/' . $model->id . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => CadScertidaoController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                        ]) : '';
            },
            'delete' => function ($url, $model) {
//                Armazena a situação da folha
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-scertidao/dlt/' . $model->id;
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
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'cad-scertidao/dpl/' . $model->id;
//                        return Yii::$app->user->identity->cadastros < 2 ? '' : Html::a(
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
        'filter' => \common\models\Listas::getCertidaoTipos(),
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return \common\models\Listas::getCertidaoTipo($model->tipo);
        }
    ];
    $columns[] = [
        'attribute' => 'certidao',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->certidao;
        }
    ];
    $columns[] = [
        'attribute' => 'emissao',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->emissao;
        }
    ];
    $columns[] = [
        'attribute' => 'cartorio',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->cartorio;
        }
    ];
    //$columns[] = [
    //'attribute' => 'uf',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->uf;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'cidade',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->cidade;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'termo',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->termo;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'livro',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->livro;
    //}
    //];
    //$columns[] = [
    //'attribute' => 'folha',
    //'format' => 'raw',
    //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
    //'value' => function ($model) {
    //    return $model->folha; 
    //}
    //];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 2 && pagamentos\controllers\FinParametrosController::getS() && $servidor->getFalecimento() == null ?
            Html::button(Yii::t('yii', 'Nova {modelClass}', [
                        'modelClass' => strtolower(Html::encode(CadScertidaoController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal . '&id_cad_servidores=' . $id_cad_servidores]),
                'title' => Yii::t('yii', 'Nova {modelClass}', [
                    'modelClass' => strtolower(Html::encode(CadScertidaoController::CLASS_VERB_NAME))
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
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
