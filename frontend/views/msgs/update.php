<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Msgs */

$this->title = Yii::t('yii', 'Update Msgs: {nameAttribute}', [
            'nameAttribute' => $model->id,
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="msgs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
