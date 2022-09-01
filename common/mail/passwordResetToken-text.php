<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Olá <?= $user->username ?>,

<?= Yii::t('yii', 'Follow the link below to reset your password:') ?>:

<?= $resetLink ?>

Atenciosamente,

<?= Yii::$app->params['application-name'] ?>
<?= Yii::$app->params['application-description'] ?>
