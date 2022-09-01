<?php

use yii\helpers\Html;
use frontend\controllers\FolhaController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Folha */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(FolhaController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Bconvenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cad-bconvenios-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
