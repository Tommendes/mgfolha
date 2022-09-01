<?php

use yii\helpers\Html;
use pagamentos\controllers\FinBasefixareferController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinBasefixarefer */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => FinBasefixareferController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Fin Basefixarefers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fin-basefixarefer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
