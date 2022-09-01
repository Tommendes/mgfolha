<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Olá <?= Html::encode($user->username) ?>,</p>

    <p><?= Yii::t('yii', 'Follow the link below to reset your password:') ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
    <br>
    <br>
    <p><em><strong>Atenciosamente,</strong></em></p>
    <p><em><strong><?= Yii::$app->params['application-name'] ?></em></strong>. 
        <?= Yii::$app->params['application-description'] ?>.</p>
</div>
