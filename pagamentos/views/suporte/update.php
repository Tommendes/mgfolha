<?php

use kartik\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\SisSuporte */

$h1 = Yii::t('yii', 'Update {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]);

//$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Sis Suportes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->title = Yii::t('yii', 'Update {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => 'Tudo em ' . SuporteController::CLASS_VERB_NAME, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Preview {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('yii', 'Update');
?>
<div class="sis-suporte-update">

    <h1><?= Html::encode($h1) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
