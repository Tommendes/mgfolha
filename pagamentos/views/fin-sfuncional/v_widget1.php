<?php

use yii\helpers\Url;
use kartik\helpers\Html;
use pagamentos\controllers\FinSfuncionalController;
use pagamentos\controllers\FinReferenciasController;
use common\models\Listas;
use pagamentos\models\FinRubricas;
use pagamentos\controllers\FinRubricasController;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\FinSfuncional */
?>
<div class="fin-sfuncional-v_widget1">

    <?php
    $referencias = FinReferenciasController::getVctoBasico(
        $model->id_cad_servidores,
        Yii::$app->user->identity->per_ano,
        Yii::$app->user->identity->per_mes,
        Yii::$app->user->identity->per_parcela
    );
    ?>
    <div class="row">
        <div class="col-12">
            <div class="list-group">
                <?= 
                    Html::a('Referências financeiras' . (Yii::$app->user->identity->administrador >= 2 ? (' ID: (' . $referencias['v_id_referencia']  . ')') : ''), '#', [
                        'value' => Url::to(['/fin-referencias/' . $referencias['v_id_referencia'], 'modal' => 1, 'mv' => '0']),
                        'title' => Yii::t('yii', 'Referências financeiras'),
                        'class' => 'showModalButton list-group-item list-group-item-action active',
                    ])
                ?>
                <?php
                ?>
                <a href="#" class="list-group-item list-group-item-action"><?= 'Este mês contém: ' . $referencias['v_dias_mes'] . ' dias' ?></a>
                <a href="#" class="list-group-item list-group-item-action"><?= 'Vínculo: ' . Listas::getVinculo($referencias['v_id_vinculo']) ?></a>
                <a href="#" class="list-group-item list-group-item-action"><?= 'Admissão em: ' . date('d-m-Y', strtotime($referencias['v_d_admissao'])) ?></a>
                <a href="#" class="list-group-item list-group-item-action"><?= $referencias['v_anos'] . ' anos ' . $referencias['v_meses'] . ' meses e ' . $referencias['v_dias'] . ' dias trabalhados' ?></a>
                <a href="#" class="list-group-item list-group-item-action"><?= 'Dias de vencimento básico: ' . $referencias['v_dias_trabalhados'] ?></a>
                <!--<a href="#" class="list-group-item list-group-item-action"><?php // echo (($label = Listas::getLabelVctoVinculo($referencias['v_id_vinculo'])) !== null ? $label : $referencias['v_id_vinculo']) . ' no período: ' . 'R$' . number_format($referencias['r_valor'], 2, ',', '.')                      
                                                                                ?></a>-->
                <a href="#" class="list-group-item list-group-item-action"><?= FinSfuncionalController::getEnioLabel($enio = $model->getCadServidor()->getEnio()) . '(s) do servidor: ' . FinSfuncionalController::getEnios($enio, $model->id_cad_servidores) ?></a>
                <a href="#" class="list-group-item list-group-item-action">Margem consignável: R$<?= $referencias['v_margemConsignavel'] > 0 ? $referencias['v_margemConsignavel'] : '0,00' ?></a>
            </div>
        </div>
    </div>
    &nbsp;
    <div class="row">
        <div class="col-12">
            <div class="list-group">
                <?php
                $rubricas = FinRubricas::find()->where([
                    FinRubricas::tableName() . '.id_cad_servidores' => $model->id_cad_servidores,
                    FinRubricas::tableName() . '.dominio' => $model->dominio,
                    FinRubricas::tableName() . '.ano' => Yii::$app->user->identity->per_ano,
                    FinRubricas::tableName() . '.mes' => Yii::$app->user->identity->per_mes,
                    FinRubricas::tableName() . '.parcela' => Yii::$app->user->identity->per_parcela,
                ])
                    ->join('join', 'fin_eventos', 'fin_eventos.id = fin_rubricas.id_fin_eventos');
                ?>
                <?php
                $rubrica = $rubricas->one();
                // echo Html::a('<span class="fas fa-eye"></span>&nbsp;' . FinRubricasController::CLASS_VERB_NAME_PL, $rubrica != null ? '#' : 'javascript:naF("rubricas");', [
                //     'value' => $rubrica != null ? Url::to(['/fin-rubricas?matricula=' . $rubrica->getCadServidor()->matricula, 'modal' => 1]) : '',
                //     'title' => Yii::t('yii', 'Rúbricas'),
                //     'class' => ($rubrica != null ? 'showModalButton' : '') . ' btn btn-outline-danger',
                // ])
                ?>
                <?php
                $rubricas = $rubricas
                    ->orderBy([
                        'fin_eventos.tipo' => SORT_ASC,
                        'fin_eventos.id_evento' => SORT_ASC,
                    ])
                    ->all();
                foreach ($rubricas as $rubrica) {
                    $evento = $rubrica->getFinEvento();
                    $credDebit = $evento->tipo ? 'danger' : 'primary';
                    $editavel = !((Yii::$app->user->identity->financeiro < 3 || $evento->automatico == 1) || !pagamentos\controllers\FinParametrosController::getS());
                    $a = '<span class="badge badge-' . $credDebit . ' badge-pill">'
                        . number_format($rubrica->valor, 2, ',', '.') .
                        '</span>';
                    $a = Html::a(($title = '(' . $evento->id_evento . ') ' . $evento->evento_nome) . $a, '#', [
                        'value' => Url::to(['/fin-rubricas/' . $rubrica->slug, 'modal' => 1, 'mv' => ($editavel ? $mv : 0)]),
                        'title' => Yii::t('yii', $title),
                        'class' => 'showModalButton list-group-item list-group-item-action d-flex justify-content-between align-items-center',
                    ]);
                ?>
                    <?= $a ?>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>