<?php

use kartik\helpers\Html;
use yii\helpers\Url;

//use kartik\icons\Icon;
//Icon::map($this, Icon::BSG);
?>
<footer>
    <div class="row">
        <div class="col-md-3">                            
            <ul style="list-style-type: none;">
                <li>                          
                    <?=
//                    Html::img(Url::home(true) . Yii::getAlias('@imgs_url') . '/solisyon_logo.png', [
//                        'class' => 'center-block',
//                        'width' => '150px'
//                    ]).
                    Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/mega_logo.png', ['style' => 'width:100%;max-width:70px;', 'title' => 'Mega Assessoria e Tecnologia']) .
                    Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/solisyon_logo_s.png', ['style' => 'width:100%;max-width:70px;', 'title' => 'Solisyon Smart Apps'])
                    ?>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <p class="text-center">
                &copy; 2001 - <?= date('Y') ?> - <?= Yii::$app->name ?> - Todos os Direitos Reservados - 
                <a style="font-weight: bold" href="<?= Url::home(true) . 'termos-uso' ?>" target="_blank">Termos de Uso</a> - <a style="font-weight: bold" href="<?= Url::home(true) . 'politica-privacidade' ?>" target="_blank">Política de Privacidade</a><br>
                <i class='fa fa-question-circle'></i>&nbsp;<a style="font-weight: bold" href="#" title="Sobre" data-toggle="modal" data-target="#md_about<?= time() ?>" data-placement="top">Sobre</a>
                &nbsp;<i class='fa fa-envelope-o'></i>&nbsp;<a style="font-weight: bold" href="#" title="Contato" data-toggle="modal" data-target="#md_contato<?= time() ?>" data-placement="top">Contato</a></p>
            <?php if (!Yii::$app->user->isGuest) : ?>
                <p class="text-center">
                    <span class="text-center label label-default">
                        <?= Yii::$app->user->identity->hash ?>
                    </span>
                    <span class="text-center label label-info">
                        <?= Yii::$app->user->identity->dominio ?>
                    </span>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-md-3">                       
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
    <!--<div class="clearfix"></div>-->
</footer>