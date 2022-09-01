<?php

use yii\helpers\Html;
use pagamentos\controllers\CadSfuncionalController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadSfuncional */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadSfuncionalController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Sfuncionals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-sfuncional-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
