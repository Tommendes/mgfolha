<?php

use yii\helpers\Html;
use pagamentos\controllers\CadCentrosController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadCentros */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadCentrosController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Centros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-centros-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
