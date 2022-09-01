<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\Folha */

$this->title = 'Impressões da folha';
$this->params['breadcrumbs'][] = ['label' => 'Folhas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$model->titulo = pagamentos\controllers\FolhaController::getImpressoes($model->titulo)['titulo'];
$model->quebra = -1;
?>
<div class="folha-create">

    <h1><?= Html::encode($this->title . ": $model->titulo") ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
