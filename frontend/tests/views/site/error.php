<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error form-transparence">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p><?= Yii::t('yii', 'The above error occurred while the Web server was processing your request.') ?></p>
    <p><?= Yii::t('yii', 'Please contact us if you think this is a server error. Thank you.') ?></p>
    <p>Para agilizar o suporte, por favor nos envie a URL(endereço da página) que está na barra de endereço do seu navegador</p>

</div>
