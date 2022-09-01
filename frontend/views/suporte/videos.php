<?php
/* @var $this yii\web\View */

use common\models\SisParams;
use frontend\models\Usuarios;
use kartik\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::$app->name;
$grupos = SisParams::find()
        ->where([
            'dominio' => 'videoaulas',
            'grupo' => 'label'
        ])
        ->groupBy('parametro')
        ->orderBy(['parametro' => SORT_ASC])
        ->all();
if (isset(Yii::$app->request->queryParams['id'])):
    $filme_atual = SisParams::findOne(['id' => Yii::$app->request->queryParams['id']]);
    $descricao_atual = SisParams::findOne([
                'dominio' => 'videoaulas',
                'parametro' => Yii::$app->request->queryParams['id'],
    ]);
else:
    $filme_atual = null;
    $descricao_atual = null;
endif;
?>
<div class="row">
    <div class="col-md-3"> 
        <button type="button" class="list-group-item active disabled">Vídeo aulas</button>
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            foreach ($grupos as $grupo) {
                $filme_pertence_ao_grupo = !is_null(Params::findOne([
                                    'id' => isset(Yii::$app->request->queryParams['id']) ? Yii::$app->request->queryParams['id'] : null,
                                    'dominio' => 'videoaulas',
                                    'grupo' => $grupo->parametro,
                                ])
                );
                $filmes_do_grupo_count = SisParams::find()
                        ->where([
                            'dominio' => 'videoaulas',
                            'grupo' => $grupo->parametro,
                        ])
                        ->count();
                ?>
                <div class="panel panel-default"> 
                    <div class="panel-heading" role="tab" id="heading<?= $grupo->parametro ?>">
                        <h4 class="panel-title">
                            <a <?= $filme_pertence_ao_grupo ? '' : 'class="collapsed"' ?> 
                                role="button" data-toggle="collapse" 
                                data-parent="#accordion" 
                                href="#collapse<?= $grupo->parametro ?>"  
                                aria-expanded="<?= $filme_pertence_ao_grupo ? 'true' : 'false' ?>" 
                                aria-controls="collapse<?= $grupo->parametro ?>"><?= $grupo->label ?>    
                            </a>
                            <span class="badge"><?= $filmes_do_grupo_count ?></span>
                        </h4>
                    </div>
                    <div id="collapse<?= $grupo->parametro ?>" class="panel-collapse collapse <?= $filme_pertence_ao_grupo ? 'in' : '' ?>" 
                         role="tabpanel" aria-labelledby="heading<?= $grupo->parametro ?>">
                        <div class="list-group">
                            <?php
                            $filmes_do_grupo = SisParams::find()
                                    ->where([
                                        'dominio' => 'videoaulas',
                                        'grupo' => $grupo->parametro,
                                    ])
                                    ->all();
                            foreach ($filmes_do_grupo as $filme_do_grupo) {
                                $filme_atual_ = isset($filme_atual->id) ? $filme_atual->id : 0;
                                ?>
                                <a href="<?= Url::home(true) ?>suporte/videos/<?= $filme_do_grupo->id ?>" 
                                   class="list-group-item <?= $filme_do_grupo->id === $filme_atual_ ? 'active' : '' ?>">
                                    <?= $filme_do_grupo->label . ($filme_do_grupo->id === $filme_atual_ ? ' ' . Html::icon('ok') : ' ' . Html::icon('play')) ?></a>  
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>   
    </div>
    <div class="col-md-9">
        <?php
        if (\Yii::$app->user->isGuest) {
            $modelComprar = new Usuarios();
            echo $this->render('comprar_horiz', [
                'modelComprar' => $modelComprar,
            ]);
        }
        ?>        
        <?php
        if (isset(Yii::$app->request->queryParams['id'])):
            ?>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe src="https://www.youtube.com/embed/<?= $filme_atual->parametro ?>?rel=0&amp;showinfo=0;autoplay=1" frameborder="0"></iframe>
            </div>
            <br>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $descricao_atual->label ?>
                </div>
            </div>
        <?php else : ?>
            <!--Painel de boas vindas da página dos vídeos-->
            <div class="well well-lg">
                <div class="row">
                    <div class="col-md-12">
                        <div class="jumbotron">  
                            <h1>Seja bem vindo à universidade <img src="<?= Url::home(true) ?>assets/_arquivos/imgs/linkos-marca.png" width="300"></h1>
                            <h2>Para começar escolha um dos 
                                materiais disponíveis no menu <?=
                                (Yii::$app->mobileDetect->isMobile() ||
                                Yii::$app->mobileDetect->isTablet()) ? '' : 'ao lado'
                                ?></h2>
                        </div>
                    </div>
                </div>        
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function toTop() {
        $('html, body').animate({
            scrollTop: 0
        }, 500, 'linear');
        $("#usuarios-email").focus();
    }
</script>