<?php

use yii\helpers\Html;
use pagamentos\controllers\CadFuncoesController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadFuncoes */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadFuncoesController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Funcoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cad-funcoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
