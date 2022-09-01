<?php
/* @var $this \yii\web\View */
/* @var $content string */

use kartik\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use yii\bootstrap4\Modal;
use kartik\dialog\Dialog;
use common\models\SisReviews;
use common\models\Orgao;

/* Força o direcionamento para https caso esteja sob http */
if (strpos(Url::base(true), 'http://') === 0 && empty(strpos(Url::base(true), 'localhost'))) {
    Yii::$app->response->redirect(str_replace('http://', 'https://', Url::base(true)));
}
echo Dialog::widget([
    'libName' => 'krajeeDialog', // optional if not set will default to `krajeeDialog`
    'options' => ['draggable' => true, 'style' => 'color: #222']//, 'closable' => true], // custom options
]);
$time = time();
AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $dominio = strtolower(trim(Yii::$app->user->identity->dominio));
    $cliente = strtolower(trim(Yii::$app->user->identity->cliente));
    $empresa = Orgao::findOne(['dominio' => Yii::$app->user->identity->dominio]);

// Gera ou garante a existência da pasta de uploads do sistema  
    $path[] = Yii::getAlias("@common_uploads_root/imagens/$cliente/$dominio/usuarios");
    foreach ($path as $path) {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <?= $this->render('mainHead') ?>
    <body onload="$('#loader').hide();" >
        <?php if (!Yii::$app->user->isGuest): ?>
            <!--Foi adicionado um loader para indicar a operação do app 
            evitando assim algum erro de clique duplo pelo usuário-->
            <div id="loader"><img class="image" src="<?= Url::home() . Yii::getAlias('@imgs_url') . '/loader.png' ?>" alt="Carregando..." width="250px;"></div>
            <!--Fim do loader-->
        <?php endif; ?>
        <?php $this->beginBody() ?>

        <?php
        if (Yii::$app->user->isGuest && Yii::$app->controller->id == 'site' && (Yii::$app->controller->action->id == 'index' || Yii::$app->controller->action->id == 'holerite-on-line')) {
            $name = Yii::$app->params['application-name'];
            $description = Yii::$app->params['application-description'];
            $o_description = strtolower(Yii::$app->params['application-object-description']);
            $html = <<<HTML
        <div class="jumbotron jumbotron-fluid text-white-50" style="padding-top: 50px;">
            <div class="container-fluid">
                <h1 class="display-4">$name</h1>
                <p class="lead">$description</p>
                <p class="lead">Nosso objetivo é $o_description</p>
            </div>
        </div>
HTML;
            echo $html;
        }
        ?>

        <div class="wrap">
            <?php
//            if (!Yii::$app->user->isGuest) {
            echo $this->render('mainMenu');
            ?>
            <div class="container-fluid<?= Yii::$app->mobileDetect->isMobile() ? '' : ('" style="padding-top: 5%;"') ?>>
            <?=
            !Yii::$app->user->isGuest ? Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
//                    'options' => [],
                    ]) : ''
            ?>
            <?php // } ?>
            <?php
            /*
             * Oferece uma mensagem de confirmação para alguma operação durante a aplicação
             */
            $alingBeg = <<<HTML
                            <div class="row"><div class="col-xs-12 col-md-offset-6 col-md-6"> 
HTML;
            $alingEnd = <<<HTML
                            </div></div>
HTML;
            foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                $hide = true;
                if (is_array($message) > 0) {
                    $message = $message[0];
                    $key = $message[1];
                    $hide = isset($message[2]) ? $message[2] : true;
                }
                if ($key != 'deg' && $key != 'dev') {
                    $js = <<< JS
                                new PNotify({ 
                                    title: 'Aviso',
                                    text: '$message', 
                                    type: '$key',
                                    styling: 'fontawesome',
                                    hide: $hide,
                                    animate: { 
                                        animate: true,
                                        in_class: 'rotateInDownLeft',
                                        out_class: 'rotateOutUpRight'
                                    }
                                });
JS;
                    $this->registerJs($js);
                }
            }
//                 echo Alert::widget()  
            ?> 
                 <?php // echo shell_exec("java -version 2>&1") . '<br>';  ?>
                 <?php // echo 'Yii::$app->language: ' . Yii::$app->language . '<br>' ?>
                 <?php // echo 'Yii::$app->db: ' . yii\helpers\Json::encode(Yii::$app->db->dsn) . '<br>' ?>
                 <?= $content ?>
    </div>
</div>
<!-- /page content -->
<?php
if ((!Yii::$app->mobileDetect->isMobile() ||
        Yii::$app->mobileDetect->isTablet())) {
    ?>
    <!-- footer content -->
    <?= $this->render('footer') ?>
    <!-- /footer content -->
<?php } ?>
<!-- Modal about -->
<div class="modal fade" style="color: #222" id="md_about<?= $time ?>" tabindex="false" role="dialog"
     aria-labelledby="md_about<?= $time ?>_Label">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #ffff99; border-radius: 5px;">
                <h4 class="modal-title">Sobre o Sistema</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php $review = SisReviews::find()->where(['status' => SisReviews::STATUS_ATIVO])->orderBy('ID DESC')->limit('1')->one(); ?>
                        <p><?= Yii::$app->params['application-name'] ?></p>
                        <p><?= Yii::$app->params['application-description'] ?>, versão <?= $review->versao . '.' . str_pad($review->lancamento, 2, 0, STR_PAD_LEFT) . '.' . str_pad($review->revisao, 3, 0, STR_PAD_LEFT) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <p>Aplicação distribuida por: <strong>Mega Assessoria e Tecnologia</strong></p>
                        <p>Desenvolvida por: <strong>Solisyon Smart Apps</strong></p>
                    </div>
                    <div class="col-md-4">
                        <p class="center-block"><?=
                            Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/mega_logo.png', ['style' => 'width:100%;max-width:40px;', 'title' => 'Mega Assessoria e Tecnologia']) .
                            Html::img(Url::home() . Yii::getAlias('@imgs_url') . '/solisyon_logo_s.png', ['style' => 'width:100%;max-width:40px;', 'title' => 'Solisyon Smart Apps'])
                            ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Modal about -->
<?php
if (!Yii::$app->user->isGuest and 1 == 2) {
    $errosFolha = frontend\controllers\FolhaController::getValidaFolha();
    $urlErros = Html::a('aqui para resolver', '#', [
                'id' => Yii::$app->security->generateRandomString(),
                'title' => Yii::t('yii', 'Erros na folha'),
                'value' => Url::to(['/folha/z']),
                'class' => 'showModalButton'
    ]);
    if ($errosFolha > 0) {
        $js = <<< JS
    new PNotify({
        title: 'Aviso',
        text: 'Há $errosFolha erros na folha de pagamento. Clique $urlErros',
        type: 'warning',
//        hide: false,
        styling: 'fontawesome',
        nonblock: {
            nonblock: true
        }, 
    });           
JS;
        $this->registerJs($js);
    }
}

$js = <<< JS
            tinymce.init({ entity_encoding : "raw" });
            $(document).on('pjax:error', function(event, xhr) {
                console.log(xhr.responseText);
                event.preventDefault();
            });
JS;
//$this->registerJs($js);
?>
<?php $this->endBody() ?>
<?php
Modal::begin([
    'title' => '<button id="all_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;&nbsp;Fechar</span></button>',
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => Modal::SIZE_LARGE . ' modal-dialog-centered',
    'options' => [
        'tabindex' => false, // important for Select2 to work properly
    ],
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => [
//        'backdrop' => 'static',
        'keyboard' => false,
    ]
]);
$loader = Url::home() . Yii::getAlias('@imgs_url');
echo "<div id='modalContent'><div style='text-align:center'><img src='$loader/loader.gif'></div></div>";
Modal::end();
?>
<?php
$url = Url::home(true) . 'logout?p=' . Url::current();
if (!Yii::$app->user->isGuest) {
    $js = <<<JS
                $('#logout').click(function () {
                    $.ajax("$url", {type: 'POST'});
                });
JS;
    $this->registerJs($js);
}
?>
</body>
<?php
if (!Yii::$app->user->isGuest) {//&& !Yii::$app->user->identity->administrador >= 1
    echo $this->render('@common/components/idle');
}
if (file_exists(Yii::getAlias('@self_update'))) {
    $url_updr = Url::home() . 'self-update.php';
    setcookie('newURL', Url::home(true));
    if (!Yii::$app->user->isGuest) {
        $js = <<< JS
        $(document).ready(function() {
            krajeeDialog.confirm("Há uma atualização disponível. Deseja atualizar agora?<br/>O sistema ficará indisponível por alguns instantes...", function (result) {
                if (result) { 
                    new PNotify({
                        title: 'Atualização em andamento',
                        text: 'A atualização iniciou e não pode ser interrompida. Normalmente custa apenas alguns segundos. Aguarde o final da atualização para depois usar o sistema. Então, por favor, sente e relaxe enquanto preparamos tudo para você.<br><sstrong>Ao final da atualização esta tela será atualizada</strong>',
                        type: 'success',
                        hide: false,
                        styling: 'fontawesome',
                        nonblock: {
                            nonblock: false
                        },
                    });           
                    window.location.replace("$url_updr");
                }
            });
        });
JS;
        $this->registerJs($js);
    }
    $js = <<< JS
        $("#self_updr").click(function () {
            krajeeDialog.confirm("Confirma a atualização? O sistema ficará indisponível por alguns minutos...", function (result) {
                if (result) { 
                    new PNotify({
                        title: 'Atualização em andamento',
                        text: 'A atualização iniciou e não pode mais ser interrompida. Só precisa ser efetuada uma vez e acontece independente desta janela. Mas enquanto não estiver pronta vc não poderá usar a aplicação. Então, por favor, sente e relaxe enquanto preparamos tudo para você.<br><sstrong>Ao final da atualização esta tela será atualizada</strong>',
                        type: 'success',
                        hide: false,
                        styling: 'fontawesome',
                        nonblock: {
                            nonblock: false
                        },
                    });           
                    window.location.replace("$url_updr");
                }
            });
        });
JS;
}
?>


</html>
<?php $this->endPage() ?>
