<?php

use yii\helpers\Html;
use pagamentos\controllers\CadDepartamentosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadDepartamentos */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadDepartamentosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Locacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-departamentos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
