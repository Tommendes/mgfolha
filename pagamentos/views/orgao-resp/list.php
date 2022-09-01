<?php

use kartik\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use pagamentos\controllers\OrgaoRespController;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrgaoUaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = OrgaoRespController::CLASS_VERB_NAME_PL;
//$this->params['breadcrumbs'][] = $this->title;

$modal = !isset($modal) ? false : $modal;
?>
<div class="orgao-resp-index">

    <?php
    Pjax::begin(['id' => 'orgao_resp_list-pjax']);
    echo ($dataProvider->count == 0 && $buttons) ? Html::button(Yii::t('yii', 'Informar {modelClass}', [
                        'modelClass' => strtolower(Html::encode(OrgaoRespController::CLASS_VERB_NAME))
                    ]), [
                'id' => 'btn_n_' . Yii::$app->controller->id,
                'value' => Url::to(['/orgao-resp/c?modal=' . $modal . '&cp=' . $cp]),
                'title' => Yii::t('yii', 'Create {modelClass}', [
                    'modelClass' => strtolower(Html::encode(OrgaoRespController::CLASS_VERB_NAME))
                ]),
                'class' => 'showModalButton btn btn-warning list-group-item',
                'style' => 'display: block; width: 100%;'
            ]) : '';
    $dataProvider->pagination->pageSize = 5;
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-group-item',
            'id' => 'list-group-item',
        ],
        'emptyText' => '',
        'summary' => '',
        'layout' => "{pager}\n{items}\n{summary}",
        'itemView' => function ($model, $key, $index, $widget) {
//            return $this->render('_list_item', ['model' => $model]);
            $msdelete = Yii::t('yii', 'Are you sure you want to exclude this item?');
            $urldelete = Url::home(true) . 'orgao-resp/dlt/' . $model->id;
            $idListPJax = 'orgao_resp_list-pjax';
            $buttons = (Yii::$app->user->identity->base_servico === 'pagamentos') && ((!Yii::$app->user->isGuest && Yii::$app->user->identity->gestor >= 1) ||
                    (isset(Yii::$app->request->queryParams['mv']) && Yii::$app->request->queryParams['mv'] > 0));
            return '> ' . $model->nome_gestor . ($buttons ? (' ' . Html::button('<span class="fa fa-eye"></span>', [
                        'value' => Url::to(['/orgao-resp/' . $model->id . '?modal=1&cp=' . $model->id_orgao . '&mv=0']),
                        'title' => Yii::t('yii', 'See {modelClass}', ['modelClass' => OrgaoRespController::CLASS_VERB_NAME]),
                        'class' => 'showModalButton button-as-link text-right'
                    ]) . Html::button('<span class="fa fa-pen-square"></span>', [
                        'value' => Url::to(['/orgao-resp/u/' . $model->id . '?modal=1&cp=' . $model->id_orgao . '&mv=' . \kartik\detail\DetailView::MODE_EDIT]),
                        'title' => Yii::t('yii', 'Updating record {modelClass}', ['modelClass' => OrgaoRespController::CLASS_VERB_NAME]),
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
            'firstPageLabel' => 'Primeira',
            'lastPageLabel' => 'Última',
            'nextPageLabel' => 'Próxima',
            'prevPageLabel' => 'Anterior',
            'maxButtonCount' => 3,
        ],
    ]);
    Pjax::end();
    ?>
</div>
