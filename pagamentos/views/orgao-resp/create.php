<?php

use yii\helpers\Html;
use pagamentos\controllers\OrgaoRespController;

/* @var $this yii\web\View */
/* @var $model common\models\OrgaoResp */

$this->title = Yii::t('yii', 'New {modelClass}', ['modelClass' => OrgaoRespController::CLASS_VERB_NAME]);
$this->title = Yii::t('yii', 'Create Orgao Resp');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Orgao Resps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orgao-resp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
