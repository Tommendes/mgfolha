<?php

use frontend\controllers\FolhaController;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);

/* @var $this yii\web\View */
/* @var $model frontend\models\Folha */

$this->title = 'Erros na ' . strtolower(FolhaController::CLASS_VERB_NAME);
\yii\web\YiiAsset::register($this);
?>
<div class="cad-bconvenios-erros">
    <?= $validacao ?>
</div>
