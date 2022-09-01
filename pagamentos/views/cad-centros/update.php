<?php

use yii\helpers\Html;
use pagamentos\controllers\CadCentrosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCentros */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadCentrosController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Centros', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cad-centros-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
