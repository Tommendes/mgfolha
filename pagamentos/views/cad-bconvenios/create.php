<?php

use yii\helpers\Html;
use pagamentos\controllers\CadBconveniosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadBconvenios */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadBconveniosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Bconvenios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-bconvenios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
