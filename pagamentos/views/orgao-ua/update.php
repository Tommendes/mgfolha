<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoUa */

$this->title = Yii::t('yii', 'Update Orgao Ua: {name}', [
            'name' => $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Orgao Uas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="orgao-ua-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
