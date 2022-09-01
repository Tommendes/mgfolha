<?php

use kartik\helpers\Html;
use frontend\controllers\SuporteController;

/* @var $this yii\web\View */
/* @var $model frontend\models\Suporte */

$this->title = SuporteController::CLASS_VERB_NAME;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Suportes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suporte-view" style="background-color: #f0f0f0">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <h1><?= Html::encode(Yii::t('yii', $model->descricao, ['app_name' => Yii::$app->name])) ?></h1>
            <?= Yii::t('yii', $model->texto, ['app_name' => Yii::$app->name]) ?>
        </div>
    </div>
</div>

<?php
$js = <<< JS
JS;
$this->registerJs($js);
?>