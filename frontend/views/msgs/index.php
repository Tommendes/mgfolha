<?php

use kartik\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\grid\ActionColumn;
use frontend\controllers\MsgsController;
use frontend\models\Msgs;
use yii\helpers\ArrayHelper;
use common\models\SisParams;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MsgsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('yii', 'Msgs');
$this->params['breadcrumbs'][] = $this->title;
$modal = isset($modal) ? $modal : false;
?> 
<div class="msgs-index grid-bg"> 

    <?php
    $status = [Msgs::STATUS_ATIVO => 'Ativo', Msgs::STATUS_INATIVO => 'Inativo', Msgs::STATUS_LIDA => 'Lida', Msgs::STATUS_CANCELADO => 'Cancelado'];
    $actColumn = [
        'class' => ActionColumn::class,
        'template' => '{view} {update} {delete} {duplicate} {replicate}',
        'width' => '118px',
        'buttons' => [
            'view' => function ($url, $model) {
                return Html::button('<span class="fa fa-eye"></span> ', [
                            'value' => Url::to(['msgs/' . $model->slug . '?modal=1&mv=0']),
                            'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => MsgsController::CLASS_VERB_NAME]),
                            'class' => 'showModalButton button-as-link',
                ]);
//                return Html::a('<span class="fa fa-eye"></span>', Url::home(true) . 'msgs/' . $model->slug, [
//                            'title' => Yii::t('yii', 'View'),
//                            'aria-label' => Yii::t('yii', 'View'),
//                ]);
            },
            'update' => function ($url, $model) {
                return (Yii::$app->user->identity->administrador < 2 || Msgs::STATUS_CANCELADO === $model->status) ? '' : Html::button('<span class="fa fa-pen-square"></span> ', [
                            'value' => Url::to(['msgs/u/' . $model->slug . '?modal=1&mv=' . kartik\detail\DetailView::MODE_EDIT]),
                            'title' => Yii::t('yii', 'Update'),
                            'class' => 'showModalButton button-as-link',
                ]);
//                return Msgs::STATUS_CANCELADO === $model->status ? '' : Html::a(
//                                '<span class="fa fa-pen-square"></span>', Url::home(true) . 'msgs/u/' . $model->slug, [
//                            'title' => Yii::t('yii', 'Update'),
//                            'aria-label' => Yii::t('yii', 'Update'),
//                ]);
            },
            'delete' => function ($url, $model) {
//                return Msgs::STATUS_CANCELADO === $model->status ? '' : Html::a(
//                                '<span class="fa fa-trash"></span>', Url::home(true) . 'msgs/dlt/' . $model->slug, [
//                            'title' => Yii::t('yii', 'Delete'),
//                            'aria-label' => Yii::t('yii', 'Delete'),
//                            'data' => [
//                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
//                                'method' => 'post',
//                            ],
//                ]);
                $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
                $urldelete = Url::home(true) . 'msgs/dlt/' . $model->slug;
                return Yii::$app->user->identity->administrador < 2 ? '' : Html::a('<span class="fa fa-trash"></span>', '#', [
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
//                return Html::a(
//                                '<span class="fa fa-clone"></span>', Url::home(true) . 'msgs/dpl/' . $model->slug, [
//                            'title' => Yii::t('yii', 'Duplicate'),
//                            'aria-label' => Yii::t('yii', 'Duplicate'),
//                            'data' => [
//                                'confirm' => Yii::t('yii', 'Are you sure you want to duplicate this item?'),
//                                'method' => 'post',
//                            ],
//                ]);

                $msduplicate = Yii::t('yii', 'Are you sure you want to duplicate this item?');
                $url = Url::home(true) . 'msgs/dpl/' . $model->slug;
                return Yii::$app->user->identity->administrador < 2 ? '' : Html::a(
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
            'replicate' => function ($url, $model) {
                return Html::a(
                                '<span class="fa fa-retweet"></span>', Url::home(true) . 'msgs/replicate/' . $model->slug, [
                            'title' => Yii::t('yii', 'Replicate'),
                            'aria-label' => Yii::t('yii', 'Replicate'),
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to replicate this item to all users?'),
                                'method' => 'post',
                            ],
                ]);
            },
        ],
    ];
    $id_desk_user = MsgsController::getDeskUsers();
    $columns[] = [
        'attribute' => 'id_desk_user',
        'filter' => ArrayHelper::map($id_desk_user, 'id', 'cli_nome'),
        'value' => function($model) {
            return $model->getDeskUser($model->id_desk_user);
        },
    ];
    $columns[] = [
        'attribute' => 'status',
        'filter' => $status,
        'value' => function($model) {
            switch ($model->status) {
                case 0 : return 'Inativo';
                    break;
                case 10 : return 'Ativo';
                    break;
                case 90 : return 'Lida';
                    break;
                case 99 : return 'Cancelado';
                    break;
            }
        },
        'label' => 'Status',
    ];
    $columns[] = [
        'attribute' => 'caption',
    ];
    $columns[] = [
        'attribute' => 'body',
        'format' => 'raw',
        'value' => function ($model) {
            return yii::t('yii', '<div class="alert" style="background-color: '
                            . '{caption_color_id};" role="alert">'
                            . '<span style="color: #ffffff;">{caption}</span></div>'
                            . $model->body, [
                        'caption' => $model->caption,
                        'caption_color_id' => $model->caption_color_id
            ]);
        },
    ];
    $columns[] = $actColumn;

    $dataProvider->pagination->pageSize = $modal ? 5 : 50;
//    Html::a(Yii::t('yii', 'Createa {modelClass}', ['modelClass' => strtolower(MsgsController::CLASS_VERB_NAME)]), ['create'], ['class' => 'btn btn-success']);
    $buttonCreate = Yii::$app->user->identity->administrador < 2 ? '' : Html::button(Yii::t('yii', 'Createa {modelClass}', [
                        'modelClass' => strtolower(MsgsController::CLASS_VERB_NAME)
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/' . Yii::$app->controller->id . '/c?modal=' . $modal]),
                'title' => Yii::t('yii', 'Createa {modelClass}', [
                    'modelClass' => strtolower(Html::encode(MsgsController::CLASS_VERB_NAME_PL))
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
                'id' => 'msgs_grid-pjax',
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
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Reset Grid'),
                    'onclick' => "$.pjax.reload({container: '#msgs_grid-pjax'});",
                ]) .
                Html::a('<i class="fa fa-filter red"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Remover todos os filtros'),
                ]),
            ],
            Yii::$app->user->identity->gestor >= 1 ? '{export}' : '',
            '{toggleData}',
                ] : null,
    ]);
    ?>
</div>
