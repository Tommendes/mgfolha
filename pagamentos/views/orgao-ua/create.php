<?php

use yii\helpers\Html;
use pagamentos\controllers\OrgaoUaController;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoUa */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => OrgaoUaController::CLASS_VERB_NAME]);
$this->title = Yii::t('yii', 'Create Orgao Ua');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Orgao Uas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgao-ua-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
