<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSfuncionalController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSfuncional */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadSfuncionalController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', CadSfuncionalController::CLASS_VERB_NAME_PL), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'See {modelClass}', [
        'modelClass' => strtolower(CadSfuncionalController::CLASS_VERB_NAME),
    ]), 'url' => ['view', 'id' => $model->slug]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Editando');
?>
<div class="cad-sfuncional-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
