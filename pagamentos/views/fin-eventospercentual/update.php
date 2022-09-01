<?php

use yii\helpers\Html;
use pagamentos\controllers\FinEventospercentualController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinEventospercentual */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(FinEventospercentualController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', FinEventospercentualController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'See {modelClass}', [
        'modelClass' => strtolower(FinEventospercentualController::CLASS_VERB_NAME),
    ]), 'url' => ['view', 'id' => $model->slug]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Editando');
?>
<div class="fin-eventospercentual-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
