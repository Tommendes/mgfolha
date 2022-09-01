<?php

use yii\helpers\Html;
use pagamentos\controllers\CadLocaltrabalhoController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadLocaltrabalho */

$this->title = Yii::t('yii', 'Updating record {modelClass}', [
            'modelClass' => strtolower(CadLocaltrabalhoController::CLASS_VERB_NAME),
        ]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Localtrabalhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cad-localtrabalho-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
