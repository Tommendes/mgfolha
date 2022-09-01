<div class="site-index form-bg">
    <div class="row">
        <div class="col">        
            <?php
            $model = [''];//explode("\n", shell_exec('git log -a --format=%h;%at;%cn;%s;%b'));
            $i = 0;
            foreach ($model as $linhas) {
                if (count($linha = explode(";", $linhas)) == 5) {
                    list($hash, $unix_time, $commiter, $assunto, $descricao) = explode(";", $linhas);
                    echo "$hash, $unix_time, $commiter, $assunto, $descricao<br>";
                }
                $i++;
            }
            ?>
        </div>
    </div>
</div>

<?php

use frontend\controllers\SisReviewsController;
use kartik\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SisReviewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('yii', 'Sis Reviews');
//$this->params['breadcrumbs'][] = $this->title;
$this->title = SisReviewsController::CLASS_VERB_NAME;
$this->params['breadcrumbs'][] = Yii::t('yii', 'Alla the ') . SisReviewsController::CLASS_VERB_NAME_PL;
?>
<link href="<?= Url::home(true) . Yii::getAlias('@assets_frontend') ?>/css/offcanvas.css" rel="stylesheet">
<div class="<?= SisReviewsController::CLASS_VERB_NAME ?>-index form-bg form-transparence">    
    <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-red rounded shadow-sm">
        <h1>Novidades e revisões do sistema</h1>
    </div>
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Últimas atualizações</h6>
        <?php
        $i = 0;
        $img_0 = ('data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_16778a6637f%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_16778a6637f%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2211.546875%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');
        $img_1 = ('data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_16778a7e7a6%20text%20%7B%20fill%3A%23e83e8c%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_16778a7e7a6%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23e83e8c%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2211.546875%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');
        ?>
        <?php foreach ($searchModel as $versao) { ?>
            <div class="media text-muted pt-3">
                <img data-src="holder.js/32x32?theme=thumb&amp;bg=<?= $i === 0 ? '007bff&amp;fg=007bff' : 'e83e8c&amp;fg=e83e8c' ?>&amp;size=1" alt="32x32" class="mr-2 rounded" style="width: 32px; height: 32px;" src="<?= $i === 0 ? $img_0 : $img_1 ?>" data-holder-rendered="true">
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark">Revisão #<?= $versao->versao . '.' . $versao->lancamento . '.' . $versao->revisao ?> em <?= Yii::$app->formatter->asDatetime($versao->updated_at, 'php:d-m-Y') ?></strong>
                    <?= $versao->descricao ?>
                </p>
            </div>

            <?php
            $i++;
        }
        ?>
    </div>
</div>