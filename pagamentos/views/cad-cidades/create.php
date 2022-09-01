<?php

use yii\helpers\Html;
use pagamentos\controllers\CadCidadesController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCidades */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadCidadesController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Cidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-cidades-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
