<?php

use kartik\helpers\Html;
use yii\helpers\Url;

//use kartik\icons\Icon;
//Icon::map($this, Icon::BSG);
?>
<footer>
    <div class="row">
        <div class="col-md-2">                            
            <ul style="list-style-type: none;">
                <li>                          
                    <?=
                    Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/mega_logo.png', ['style' => 'width:100%;max-width:70px;', 'title' => 'Distribuído por Mega Assessoria e Tecnologia']) .
                    Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/solisyon_logo_s.png', ['style' => 'width:100%;max-width:70px;', 'title' => 'Desenvolvido por Solisyon Smart Apps'])
                    ?>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
            <p class="text-center">
                &copy; 2001 - <?= date('Y') ?> - <?= Yii::$app->name ?> - Todos os Direitos Reservados - 
                <a style="font-weight: bold" href="<?= Url::home(true) . 'termos-uso' ?>" target="_blank">Termos de Uso</a> - <a style="font-weight: bold" href="<?= Url::home(true) . 'politica-privacidade' ?>" target="_blank">Política de Privacidade</a><br>
                <i class='fa fa-question-circle'></i>&nbsp;<a style="font-weight: bold" href="#" title="Sobre" data-toggle="modal" data-target="#md_about<?= time() ?>" data-placement="top">Sobre</a>
                &nbsp;<i class='fa fa-envelope'></i>&nbsp;<?=
                Html::a('Contato', '#', [
                    'id' => Yii::$app->security->generateRandomString(),
                    'title' => Yii::t('yii', 'Contato'),
                    'value' => Url::to(['/site/contato']),
                    'class' => 'showModalButton',
                    'style' => 'font-weight: bold',
                ]);
                ?></p>
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->status == common\models\User::STATUS_ATIVO) : ?>
                <p class="text-center">
                    <span class="text-center badge badge-secondary">
                        <?= Yii::$app->user->identity->hash ?>
                    </span>
                    <span class="text-center badge badge-info">
                        <?= Yii::$app->user->identity->base_servico . ' ' . Yii::$app->user->identity->cliente . ' ' . Yii::$app->user->identity->dominio ?>
                    </span>
                    <span id="idlelbl" class="text-center badge badge-warning"></span>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-md-2">                       
            <ul style="list-style-type: none;">
                <li>                          
                    <?=
                    Html::img(Url::home(true) . Yii::getAlias('@imgs_url') . '/comodo-ssl.png', [
                        'class' => 'center-block',
                        'width' => '150px'
                    ])
                    ?>
                </li>
            </ul>
        </div>
    </div>
</footer>