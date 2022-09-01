<?php

use yii\helpers\Html;
use pagamentos\controllers\CadFuncoesController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadFuncoes */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadFuncoesController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Funcoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-funcoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
