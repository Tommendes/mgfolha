<?php

use yii\helpers\Html;
use pagamentos\controllers\CadLocaltrabalhoController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadLocaltrabalho */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => CadLocaltrabalhoController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Cad Localtrabalhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cad-localtrabalho-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'modal' => $modal,
    ])
    ?>

</div>
