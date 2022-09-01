<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisShortener */
$t = '';
if (!$t == 's') {
    $this->title = Yii::t('yii', 'Encurtar Url');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('yii', 'Url curtas'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="sis-shortener-create">
    <?php if (!$t == 's') { ?>
        <h1><?= Html::encode($this->title) ?></h1>
    <?php } ?>

    <?=
    $this->render('_form', [
        'model' => $model,
        't' => $t
    ])
    ?>

</div>
