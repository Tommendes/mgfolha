<?php

use yii\helpers\Html;
use frontend\controllers\FolhaController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Folha */

$this->title = Yii::t('yii', '{operacao} {modelClass} {folha}', ['operacao' => 'Registrar', 'modelClass' => strtolower(FolhaController::CLASS_VERB_NAME), 'folha' => frontend\controllers\SiteController::getFolhaAtual()]);
$this->params['breadcrumbs'][] = ['label' => FolhaController::CLASS_VERB_NAME, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-bconvenios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
