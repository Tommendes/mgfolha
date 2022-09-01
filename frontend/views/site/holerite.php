<?php
/* @var $this yii\web\View */
 
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;

$this->title = 'Holerite Online';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-holerite-on-line">
    <div class="<?= isset($size) && !empty($size) && $size == 'sm' ? 'offset-md-4 col-md-4 ' : '' ?>form-bg">
        <h1 class="text-center"><?= Html::encode('Validação de holerite Online') ?></h1>
        <p>Informe um token no formulário abaixo para validar um contracheque:</p>
        <?php
        $idForm = Yii::$app->security->generateRandomString(8) . '-form';
        $form = ActiveForm::begin(['id' => $idForm, 'action' => Url::home(true) . 'holerite-on-line']);
        ?>
        <?= $form->field($model, 'token')->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('token')])->label('Validar um holerite') ?>
        <?= $form->field($model, 'ano')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'mes')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'parcela')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'id_cad_servidor')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'cliente')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'reCaptcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha2::className(), ['widgetOptions' => ['id' => Yii::$app->security->generateRandomString(8)]])->label(false) ?>

        <div class="form-group">
            <?php echo Html::submitButton(Yii::t('yii', 'Validate'), ['class' => 'btn btn-warning', 'name' => 'seach-button', 'style' => 'display: block; width: 100%;']) ?>
        </div>

        <div class="clearfix"></div>
        <?php
        ActiveForm::end();
//            echo Json::encode($modelValido);
        ?>
    </div>
    <?= $this->render('_validacao_holerite', ['modelValido' => $modelValido, 'model' => $model]) ?>
</div>