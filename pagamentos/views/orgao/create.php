<?php

use kartik\helpers\Html;
use pagamentos\controllers\OrgaoController;

/* @var $this yii\web\View */
/* @var $model common\models\Orgao */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => OrgaoController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => OrgaoController::CLASS_VERB_NAME_PL, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
