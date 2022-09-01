<?php

use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */


//$this->title = '';
?>
<div class="site-index">
    <div class="row">
        <div class="col">        
            <?php
            $model = new \frontend\models\Holerite(['token' => $token]);
            $modelValido = null;
            echo $this->render('holerite', [
                'model' => $model,
                'modelValido' => $modelValido,
            ]);
            ?>
        </div>
        <?php if (!Yii::$app->mobileDetect->isMobile()) { ?>
            <div class="col">
            </div>
            <div class="col">  
            </div>
        <?php } ?>
        <div class="col">
            <?php if (Yii::$app->user->isGuest) { ?>
                <?=
                Html::button('<i class="fas fa-sign-in-alt"></i> Acessar o sistema', [
                    'value' => Url::to(['/login']),
                    'title' => Yii::t('yii', 'Acessar o sistema'),
                    'class' => 'showModalButton modal-350 modal-title-null btn btn-light btn-lg w-100',
                ]);
                ?>
                <?=
                Html::button('<i class="fas fa-user-plus"></i> Novo usuário', [
                    'value' => Url::to(['/signup']),
                    'title' => Yii::t('yii', 'Novo usuário'),
                    'class' => 'showModalButton modal-350 modal-title-null btn btn-light btn-lg w-100',
                ]);
                ?>
            <?php } ?>  
        </div>
    </div>
</div>
<?= $this->render('_validacao_holerite', ['modelValido' => $modelValido, 'model' => $model]) ?>
