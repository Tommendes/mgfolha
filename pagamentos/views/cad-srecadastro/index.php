<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use pagamentos\controllers\CadSrecadastroController;
use pagamentos\controllers\AppController;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadSrecadastroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CadSrecadastroController::CLASS_VERB_NAME_PL;
$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
$servidor = CadScertidaoController::getCadServidor($id_cad_servidores);
?>
<div class="cad-srecadastro-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {print}',
        'width' => '70px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Yii::$app->user->identity->cadastros < 1 ? '' : Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['cad-srecadastro/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => CadSrecadastroController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
            },
            'update' => function ($url, $model) {
                return '';
//                Yii::$app->user->identity->cadastros < 3 ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
//                            'value' => Url::to(['cad-srecadastro/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
//                            'title' => Yii::t('yii', 'Updating {modelClass}', ['modelClass' => CadSrecadastroController::CLASS_VERB_NAME]),
//                            'class' => 'showModalButton button-as-link',
//                ]);
                //Html::button('<span class="fa fa-pen-square"></span>', [
                //'value' => Url::to(['cad-srecadastro/u/' . $model->slug . '?modal=1']),
                //            'title' => Yii::t('yii', 'Updating record {modelClass}',
                //            ['modelClass' => CadSrecadastroController::CLASS_VERB_NAME]),
                //            'class' => 'showModalButton button-as-link'
                //]);
            },
            'delete' => function ($url, $model) {
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'cad-srecadastro/dlt/' . $model->slug;
                $idGridPJax = Yii::$app->controller->id . '_grid-pjax';
                return Yii::$app->user->identity->cadastros < 4 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
            'print' => function ($url, $model) {
                $msprint = Yii::t('yii', 'Confirma a impressão da ficha cadastral?<br>Isso criará um novo registro de recadastro');
                $url = Url::home(true) . 'cad-srecadastro/dpl/' . $model->slug;
                return Yii::$app->user->identity->cadastros < 2 || !pagamentos\controllers\FinParametrosController::getS() ? '' : Html::a(
                                '<span class="fa fa-print"></span>', '#', [
                            'title' => Yii::t('yii', 'Print') . ' recadastro',
                            'aria-label' => Yii::t('yii', 'Print'),
                            'onclick' => "
                                krajeeDialog.confirm('$msprint', function(out){
                                    if(out) {
                                        krajeeDialog.alert('Será impressa uma ficha existente?');
                                    }
                                })
                            ",
                ]);
            },
        ],
    ];

    //    $columns[] = ['class' => 'kartik\grid\SerialColumn'];

    $columns[] = [
        'attribute' => 'id_user_recadastro',
        //'contentOptions' => ['style' => 'width: 75px;text-align: right;'],
        'value' => function ($model) {
            return AppController::tratar_nome((($username = common\models\User::findOne($model->id_user_recadastro)) != null) ? $username->username : 'Não definido');
        },
        'format' => 'raw',
    ];
    $columns[] = [
        'attribute' => 'created_at',
        'format' => 'raw',
        'label' => 'Data do recadastro',
        'contentOptions' => ['style' => 'width: 200px;text-align: right;'],
        'value' => function ($model) {
            return date('d-m-Y H:i:s', $model->created_at);
        }
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;

    $msprint = Yii::t('yii', 'Confirma a impressão da ficha cadastral?<br>Isso criará um novo registro de recadastro');
    $url = Url::to(['cad-srecadastro/p/' . $id_cad_servidores]);
    $buttonCreate = Yii::$app->user->identity->cadastros < 3 || !pagamentos\controllers\FinParametrosController::getS() || $servidor->getFalecimento() !== null ? '' : Html::button('<span class="fa fa-print"></span> '
                    . Yii::t('yii', 'Create {modelClass}', [
                        'modelClass' => strtolower(Html::encode(CadSrecadastroController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'title' => Yii::t('yii', 'Print') . ' recadastro',
                'aria-label' => Yii::t('yii', 'Print'),
                'onclick' => "
                    krajeeDialog.confirm('$msprint', function(result){
                            if(result) {
                                krajeeDialog.alert('Será impresso');
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
