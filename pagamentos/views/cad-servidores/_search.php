<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model pagamentos\models\CadServidoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<form class="form-inline" id="<?= $id_form = Yii::$app->security->generateRandomString() ?>" action="<?= Url::to(['/cad-servidores/index']) ?>" method="get">
    <input type="text" id="cadservidoressearch-nome" 
           class="form-control mr-md-auto" name="CadServidoresSearch[mixNome]" 
           placeholder="Pesquise um servidor" 
           aria-label="Servidor" 
           title="Digite a matricula(apenas os números, sem os zeros à esquerda), 
           parte do nome ou do CPF(apenas os números, sem os pontos ou hífen) do servidor" 
           aria-describedby="basic-addon2"
           data-toggle="tooltip"  
           data-placement="bottom"
           >
    <div class="help-block"></div>
    <button class="btn btn-outline-success my-2 my-sm-0 hide" type="submit"
            title="Clique para pesquisar" 
            data-toggle="tooltip" 
            data-placement="bottom"
            ><i class="fa fa-search"></i></button>
</form>

<?php
$js = <<<JS
    $('#cadservidoressearch-nome').blur(function(){
        $('#cadservidoressearch-nome').val($.trim($('#cadservidoressearch-nome').val()));
        if ($('#cadservidoressearch-nome').val().length > 0){
            $("#$id_form").submit();
        }
    })
JS;
$this->registerJs($js);
?>
