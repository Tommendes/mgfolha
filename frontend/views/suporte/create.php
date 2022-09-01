<?php

use kartik\helpers\Html;
use common\models\User;
use frontend\controllers\SuporteController;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisSuporte */

$h1 = Yii::t('yii', 'Create record {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]);

//$this->title = Yii::t('yii', 'Create Sis Suporte');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Sis Suportes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
$this->title = Yii::t('yii', 'Create record {modelClass}', ['modelClass' => SuporteController::CLASS_VERB_NAME]);
$this->params['breadcrumbs'][] = ['label' => SuporteController::CLASS_VERB_NAME, 'url' => ['index']];
$this->params['breadcrumbs'][] = $h1;
?>
<!--<div class="sis-suporte-create">-->
<div class="<?= SuporteController::CLASS_VERB_NAME ?>-Create record">

    <h1><?= Html::encode($h1) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
