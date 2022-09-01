<?php

use kartik\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use pagamentos\controllers\OrgaoUaController;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrgaoUaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = OrgaoUaController::CLASS_VERB_NAME_PL;
//$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="orgao-ua-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    Pjax::begin(['id' => 'orgao_ua_list-pjax']);
    $lblCreateBtn = $dataProvider->count == 0 ? 'Informar' : 'Createa';

    $dataProvider->pagination->pageSize = 5;
    echo ($buttons ? (Html::button(Yii::t('yii', $lblCreateBtn . ' {modelClass}', [
                        'modelClass' => strtolower(Html::encode(OrgaoUaController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/orgao-ua/c?modal=' . $modal . '&cp=' . $cp]),
                'title' => Yii::t('yii', 'Nova {modelClass}', [
                    'modelClass' => strtolower(Html::encode(OrgaoUaController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning list-group-item',
                'style' => 'display: block; width: 100%;'
            ])) : '') . ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-group-item',
            'id' => 'list-group-item',
        ],
        'itemOptions' => function ($model, $key, $index, $widget) {
            if ($key % 2 === 0) {
                return ['style' => 'background-color: #e2e3e5;'];
            }
        },
        'emptyText' => '',
        'summary' => '',
        'layout' => "{pager}\n{items}\n{summary}",
        'itemView' => function ($model, $key, $index, $widget) {
            $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
            $urldelete = Url::home(true) . 'orgao-ua/dlt/' . $model->id;
            $idListPJax = 'orgao_ua_list-pjax';
            $buttons = (Yii::$app->user->identity->base_servico === 'pagamentos') && ((!Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1) ||
                    (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] > 0));
            return '> ' . $model->nome . ' ' . ($buttons ? (Html::button('<span class="fa fa-eye"></span>', [
                        'value' => Url::to(['/orgao-ua/' . $model->id . '?modal=1&cp=' . $model->id_orgao . '&mv=0']),
                        'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => OrgaoUaController::CLASS_VERB_NAME]),
                        'class' => 'showModalButton button-as-link text-right'
                    ]) . Html::button('<span class="fa fa-pen-square"></span>', [
                        'value' => Url::to(['/orgao-ua/u/' . $model->id . '?modal=1&cp=' . $model->id_orgao . '&mv=' . \kartik\detail\DetailView::MODE_EDIT]),
                        'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => OrgaoUaController::CLASS_VERB_NAME]),
                        'class' => 'showModalButton button-as-link text-right'
                    ]) .
                    Html::button('<span class="fa fa-trash"></span>', [
                        'class' => 'button-as-link',
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
                                $.pjax.reload({container: '#$idListPJax'});
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
                    ])) : '');
        },
        'pager' => [
            'firstPageLabel' => 'Primeira&nbsp;',
            'lastPageLabel' => '&nbsp;Última',
            'nextPageLabel' => '&nbsp;Próxima',
            'prevPageLabel' => 'Anterior&nbsp;',
            'maxButtonCount' => 3,
        ],
    ]);
    Pjax::end();
    ?>
</div>
