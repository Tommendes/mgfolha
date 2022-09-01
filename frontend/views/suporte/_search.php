<?php

use kartik\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model frontend\models\SisSuporteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sis-suporte-search">

    <?php
    $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
    ]);
    ?>

    <div class="row">
        <div class="col-md-12">
            <?php echo $form->field($model, 'id') ?>

            <?php echo $form->field($model, 'dominio') ?>

            <?php echo $form->field($model, 'grupo') ?>

            <?php echo $form->field($model, 'slug') ?>

            <?php echo $form->field($model, 'descricao') ?>

            <?php // echo $form->field($model, 'texto') ?>

            <?php // echo $form->field($model, 'status') ?>

            <?php // echo $form->field($model, 'evento') ?>

            <?php // echo $form->field($model, 'created_at') ?>

            <?php // echo $form->field($model, 'updated_at')  ?>

        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('yii', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('yii', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
