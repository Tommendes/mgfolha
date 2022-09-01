<?php

use yii\helpers\Html;
use pagamentos\controllers\OrgaoRespController;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoResp */

$this->title = Yii::t('yii', 'Update Orgao Resp: {name}', [
            'name' => $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Orgao Resps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="orgao-resp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
