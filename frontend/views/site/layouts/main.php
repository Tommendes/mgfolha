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
use frontend\models\Empresa;
use frontend\models\ContactForm;

//use kartik\icons\Icon;
//
//Icon::map($this, Icon::FAB);
/* Força o direcionamento para https caso esteja sob http */
//if ((!substr(Url::base(true), 0, 6) === 'http://localhost') || (substr(Url::base(true), 0, 5) === 'http:')) {
//    Yii::$app->response->redirect(str_replace('http', 'https', Url::base(true)));
//}
if (!strpos(Url::base(true), 'localhost') && (substr(Url::base(true), 0, 5) === 'http:')) {
    Yii::$app->response->redirect(str_replace('http', 'https', Url::base(true)));
}

// krajeeDialog object
echo Dialog::widget([
    'libName' => 'krajeeDialog', // optional if not set will default to `krajeeDialog`
    'options' => ['draggable' => true, 'style' => 'color: #222']//, 'closable' => true], // custom options
]);
$time = time();
AppAsset::register($this);

if (!Yii::$app->user->isGuest) {
    $dominio = Yii::$app->user->identity->dominio;
    $empresa = Empresa::findOne(['dominio' => Yii::$app->user->identity->dominio]);
    $user_pic = DIRECTORY_SEPARATOR . $dominio . '/users/' . str_replace(' ', '', strtolower(Yii::$app->user->identity->username));
    $user_pic_root = Yii::getAlias('@uploads_root') . $user_pic . DIRECTORY_SEPARATOR . 'face.jpg';
    $user_pic_file = null;
    if (file_exists($user_pic_root)) {
        $user_pic_file = Url::home() . Yii::getAlias('@uploads_url') . $user_pic . DIRECTORY_SEPARATOR . 'face.jpg';
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

        <div class="wrap">
            <?=
            $this->render('mainMenu', [])
            ?>
            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?php
                /*
                 * Oferece uma mensagem de confirmação para alguam operação durante a aplicação
                 */
                $alingBeg = <<<HTML
                            <div class="row"><div class="col-xs-12 col-md-offset-6 col-md-6">
HTML;
                $alingEnd = <<<HTML
                            </div></div>
HTML;
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    if ($key != 'deg' && $key != 'dev') {
                        $js = <<< JS
                                new PNotify({ 
                                    title: 'Aviso',
                                    text: '$message',
                                    type: '$key',
                                    styling: 'fontawesome',
//                                    nonblock: {
//                                        nonblock: false,
//                                    },
                                });
JS;
                        $this->registerJs($js);
                    }
                }
//                 echo Alert::widget() 
                ?>
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
            <div class="modal-dialog" role="document">
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
                    <div class="modal-footer">
                        <p class="pull-right">
                            <?php
                            if (!Yii::$app->user->isGuest) {
                                $empresa = Empresa::findOne(['dominio' => Yii::$app->user->identity->dominio]);
                                ?>
                                Produto licenciado para uso exclusivo<?= $empresa != null ? ': ' . $empresa->razaosocial : '' ?>
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modal about -->
        <!-- Modal contato-->
        <div class="modal fade" style="color: #222" id="md_contato<?= $time ?>" tabindex="-1" role="dialog"
             aria-labelledby="md_contato<?= $time ?>_Label">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #ffff99; border-radius: 5px;">
                        <h4 class="modal-title">Contato</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $model = new ContactForm();
                        echo $this->render('/site/contact', [
                            'model' => $model,
                            'time' => $time,
                        ]);
                        ?>
                    </div>
                    <div class="modal-footer">
                        <p class="pull-right">
                            Sua mensagem será respondida o mais breve possível
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Modal contato-->

        <?php
        $js = <<< JS
            tinymce.init({ entity_encoding : "raw" });
            $(document).on('pjax:error', function(event, xhr) {
                console.log(xhr.responseText);
                event.preventDefault();
            });
JS;
        $this->registerJs($js);
        ?>
        <?php $this->endBody() ?>
        <?php
        Modal::begin([
            'header' => '<button id="all_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;&nbsp;Fechar</span></button>',
            'headerOptions' => ['id' => 'modalHeader'],
            'id' => 'modal',
            'size' => 'modal-lg',
            'options' => [
                'tabindex' => false, // important for Select2 to work properly
            ],
            //keeps from closing modal with esc key or by clicking out of the modal.
            // user must click cancel or X to close
            'clientOptions' => [
                'backdrop' => 'static',
                'keyboard' => false,
            ]
        ]);
        $loader = Url::home() . Yii::getAlias('@imgs_url');
        echo "<div id='modalContent'><div style='text-align:center'><img src='$loader/loader.gif'></div></div>";
        Modal::end();
        ?>
        <?php
        $url = Url::home(true) . 'logout';
        if (!Yii::$app->user->isGuest) {
            $js = <<<JS
                $('#logout').click(function () {
                    $.ajax("$url", {
                         type: 'POST'
                    });
                });
JS;
            $this->registerJs($js);
            $css = <<<CSS
                .profile-img{margin-top: -5px;margin-right: 5px;float: left;background: url($user_pic_file) 50% 50% no-repeat;background-size: auto 100%;width: 30px;height: 30px;}
CSS;
            $this->registerCss($css);
            include_once 'botoesDoMenu.php';
        }
        ?>
    </body>
</html>
<?php $this->endPage() ?>
