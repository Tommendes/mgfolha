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

    <p><?= Yii::t('yii', 'The above error occurred while the Web server was processing your request.') ?>.</p>
    <!--<p>Por favor verifique se a folha e parcela informados estão corretos. Tente fazer a mesma operação em outra folha e parcela. Tente também pesquisar outro servidor caso o erro tenha ocorrido enquanto pesquisava servidores.</p>-->
    <div class="alert alert-info">
        <p>Entre em contato conosco se você acha que isso é um erro no servidor. Mas para agilizar o suporte, por favor nos envie estes dados entre aspas: "<?= $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . '. Periodo: ' . Yii::$app->user->identity->per_ano . '|' . Yii::$app->user->identity->per_mes . '|' . Yii::$app->user->identity->per_parcela ?>"</p>
    </div>
</div>
