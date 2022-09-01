<?php
/*
 * Insere o cabeçalho da aplicação
 */

use yii\helpers\Url;
use kartik\helpers\Html;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="application-name" content="<?= Yii::$app->params['application-name'] ?>" /> 
    <meta name="msapplication-TileColor" content="#ffffff" />    

    <meta name="description" content="O <?= Yii::$app->params['application-name'] ?> é um programa de <?= Yii::$app->params['application-description'] ?>" />
    <meta name="keywords" content="gestão de pessoas, rh" />
    <meta name="author" content="Solisyon" />
    <meta name="robots" content="index, follow" />

    <link rel="shortcut icon" href="<?= Url::home(true) . Yii::getAlias('@imgs_url') ?>/icone.png"> 
    <link rel="alternate" hreflang="<?= Yii::$app->language ?>" href="<?php echo Url::home(true) ?>" />
    
    <?php include Yii::getAlias('@common/components/glAnalytics.php')?> 

    <!--Armazenar a localização do usuário--> 
    <?php
    if (!($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1')) {
        echo $this->render('@common/components/geoLocation');
    }
    ?>
    <!-- /Armazenar a localização do usuário-->

    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->params['application-name'] . (strlen($this->title) > 0 ? ' | ' . Html::encode($this->title) : '' ) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        body {
            background-image: url(<?= Url::home(true) . Yii::getAlias('@imgs_url') ?>/bg_.jpg);
        }
    </style>
</head>