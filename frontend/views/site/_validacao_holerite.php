<?php 
/* @var $this yii\web\View */
?>
<div class="modal fade" id="validarHolerite" tabindex="-1" role="dialog" aria-labelledby="validarHoleriteTitle" aria-hidden="true">
    <div class="modal-dialog modal-<?= (!is_null($modelValido) && is_array($modelValido)) ? (Yii::$app->mobileDetect->isMobile() ? 'sm' : 'md') : '' ?> modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validarHoleriteTitle">Validação de holerite Online</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if (!is_null($modelValido) && is_array($modelValido)) { ?>
                        <div class="col-sm-12 text-lg-center text-primary">O token informado é válido e os dados remuneratórios do período são:</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6"><strong>Servidor:</strong> <?= $modelValido['nome'] ?></div>
                        <div class="col-sm-12 col-md-3"><strong>Matrícula:</strong> <?= str_pad($modelValido['matricula'], 8, '0', STR_PAD_LEFT) ?></div>
                        <div class="col-sm-12 col-md-3"><strong>CPF:</strong> <?= \common\controllers\AppController::setCpfCnpjMask($modelValido['cpf']) ?></div>
                        <div class="col-sm-12 col-md-3"><strong>Folha:</strong> <?= $modelValido['mes'] . '.' . $modelValido['ano'] . '.' . $modelValido['parcela'] ?></div>
                        <div class="col-sm-12 col-md-9"><strong>Salário Líquido e Margem Consignável:</strong> R$ <?= number_format($modelValido['liquido'], 2, ',', '.') ?> | R$ <?= number_format($modelValido['margemConsignavel'], 2, ',', '.') ?></div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12 text-lg-center text-primary">O token informado não é válido! Tente novamente...</div>
                </div>
            <?php } ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="window.location.replace('<?= \yii\helpers\Url::home() ?>')">Fechar</button>
        </div>
    </div>
</div>
</div>

<?php
$js = <<< JS
    const openModal = () => $('#validarHolerite').modal('show')
    openModal()
JS;
isset($model->token) && !empty($model->token) ? $this->registerJs($js) : '';
?>

