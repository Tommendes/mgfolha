<?php

use kartik\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('yii', 'Support');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-support">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-offset-3 col-md-6"> 
            <div class="panel panel-default" style="opacity: .85">
                <div class="panel-body">
                    <p>Aqui você poderá obter suporte online baixando a versão mais recente para o aplicativo de suporte</p>
                    <p>O TeamViewer QuickSupport é um módulo de cliente muito compacto, que não precisa ser instalado
                        e que não precisa de direitos administrativos no computador remoto</p>
                    <p>Ou <a href="<?= Yii::getAlias('@web') ?>/site/contato">Contate-nos</a> para maiores informações.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-5 col-md-2"> 
            <!-- TeamViewer Logo (generated at https://www.teamviewer.com) -->
            <div style="position:relative; width:200px; height:125px;">
                <a href="https://download.teamviewer.com/download/TeamViewerQS_pt.exe" style="text-decoration:none;">
                    <img src="https://www.teamviewer.com/link/?url=246800&id=1379033218" alt="TeamViewer para suporte remoto" title="TeamViewer para suporte remoto" border="0" width="200" height="125" />
                    <span style="position:absolute; top:74.5px; left:5px; display:block; cursor:pointer; color:White; font-family:Arial; font-size:15px; line-height:1.2em; font-weight:bold; text-align:center; width:190px;">
                        Usar o TeamViewer para suporte remoto!
                    </span>
                </a>
            </div>

        </div>
    </div>
</div>