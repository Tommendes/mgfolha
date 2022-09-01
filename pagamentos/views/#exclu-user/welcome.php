<?php
/* @var $this yii\web\View */

use kartik\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$this->title = Yii::t('yii', 'Seja bem-vindo!');

$body = '<h2>Olá' . (strlen(trim($model->username)) > 0 && trim($model->username) != 'novousuario' ? ' ' . trim($model->username) : '') . ', tudo bem?</h2>'
        . '<p>A partir de agora, o <em><strong>' . Yii::$app->name . '</em></strong> é seu parceiro para '
        . strtolower(Yii::$app->params['application-object-description']) . '!</p>'
        . '<p>Vamos fazer de tudo para que você tenha uma experiência incrível! ;-)</p>'
        . '<p>Mas antes de continuar, você deve ir até a sua caixa de email, '
        . 'localizar nossa mensagem, copiar o código enviado e informar ao gestor '
        . 'para que ele libere seu acesso.</p>'
        . '<p>Seja bem-vindo e conte com a gente para o que precisar.</p>'
        . '<p>Está com dúvidas? Ligue pra gente!</p>'
        . '<p><strong>' . Yii::$app->params['telContactNumber'] . '</strong></p>'
        . '<p><em><strong>' . Yii::$app->params['application-name'] . '</em></strong> '
        . '<p><em><strong>' . Yii::$app->params['application-description'] . '</em></strong>.</p>';
?>
<div class="site-welcome">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-12"> 
            <?= $body ?>
        </div>
    </div> 
</div>