<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\CadSferiasController;
use pagamentos\controllers\CadServidoresController;
use pagamentos\controllers\AppController;
use pagamentos\models\CadSferias;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadSferiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$servidor = CadSferias::getCadServidor($id_cad_servidores);
$this->title = CadSferiasController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = ['label' => CadServidoresController::CLASS_VERB_NAME . ' ' . AppController::tratar_nome($servidor->nome), 'url' => ['/cad-servidores/view', 'id' => $servidor->slug]];
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="cad-sferias-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete}', // {duplicate}
        'width' => Yii::$app->user->identity->base_servico == 'pagamentos' ? '95px' : '30px',
        'buttons' => [
            'view' => function ($url, $model) {
                $baseServico = Yii::$app->user->identity->base_servico;
                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(["../$baseServico/cad-sferias/" . $model->slug, 'modal' => '1', 'mv' => '0', 'reloadOnClose' => true,]),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadSferiasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 3 && pagamentos\controllers\FinParametrosController::getS() ?
                        Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['cad-sferias/' . $model->slug, 'modal' => '1', 'mv' => kartik\detail\DetailView::MODE_EDIT, 'reloadOnClose' => true,]),
                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => CadSferiasController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                        ]) : '';
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['cad-sferias/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => CadSferiasController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-sferias/dlt/' . $model->slug;
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
                                            $('#closeAutoModalHeader').click();
//                                            $.pjax.reload({container: '#$idGridPJax'});
                                            if (response['reloadPage'] == true){
                                                javascript:location.reload();
                                            } 
                                        },
                                        error: function (response) {
                                            new PNotify({
                                                title: 'Erro',
                                                text: response['mess'],
                                                type: response['class'], 
                                                styling: 'fontawesome',
                                                animate: {
                                                    animate: true,
                                                    in_class: 'rotateInDownLeft',
                                                    out_class: 'rotateOutUpRight'
                                                }
                                            });
                                        }
                                    });
                                    return false;
                                }
                                return false;
                            ",
                        ]) : '';
            },
//                    'duplicate' => function ($url, $model) {
//                        $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
//                        $url = Url::home(true) . 'cad-sferias/dpl/' . $model->slug;
//                        return Yii::$app->user->identity->cadastros < 2 || !pagamentos\controllers\FinParametrosController::getS() ? 
//                            '' : Html::a(
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
        'attribute' => 'd_ferias',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->d_ferias;
        }
    ];
    $columns[] = [
        'attribute' => 'periodo',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->periodo;
        }
    ];
    $columns[] = [
        'attribute' => 'observacoes',
        'format' => 'raw',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return $model->observacoes;
        }
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
    $buttonCreate = Yii::$app->user->identity->base_servico == 'pagamentos' && Yii::$app->user->identity->cadastros >= 2 && pagamentos\controllers\FinParametrosController::getS() && $servidor->getFalecimento() == null ?
            Html::button(Yii::t('yii', 'Nova {modelClass}', [
                        'modelClass' => strtolower(Html::encode(CadSferiasController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal . '&id_cad_servidores=' . $id_cad_servidores]),
                'title' => Yii::t('yii', 'Nova {modelClass}', [
                    'modelClass' => strtolower(Html::encode(CadSferiasController::CLASS_VERB_NAME))
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
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
