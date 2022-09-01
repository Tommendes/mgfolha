<?php

use yii\helpers\Html;
use pagamentos\controllers\CadCargosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCargos */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadCargosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Cargos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-cargos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
