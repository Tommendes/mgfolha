<?php

use yii\helpers\Html;
use pagamentos\controllers\FinRubricasController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinRubricas */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinRubricasController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin Rubricas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-rubricas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
