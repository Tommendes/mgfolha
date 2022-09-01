<?php

use kartik\helpers\Html;
use common\controllers\SisParamsController;

/* @var $this yii\web\View */
/* @var $model common\models\SisParams */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => SisParamsController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => SisParamsController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="params-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
