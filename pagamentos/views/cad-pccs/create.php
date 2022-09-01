<?php

use yii\helpers\Html;
use pagamentos\controllers\CadPccsController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadPccs */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadPccsController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Pccs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-pccs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
