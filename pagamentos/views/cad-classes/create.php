<?php

use yii\helpers\Html;
use pagamentos\controllers\CadClassesController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadClasses */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadClassesController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Classes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-classes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
