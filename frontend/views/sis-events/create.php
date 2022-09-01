<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SisEvents */

$this->title = Yii::t('yii', 'Create SisEvents');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'SisEvents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="eventos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
