<?php

use common\controllers\AppController;
use kartik\helpers\Html;
use yii\helpers\Url;
use pagamentos\controllers\CadServidoresController;
use common\models\Listas;
use common\models\User;
use pagamentos\models\CadScertidao;
use pagamentos\models\CadServidores;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $searchModel pagamentos\models\CadServidoresSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scripts de extração de ' . strtolower(CadServidoresController::CLASS_VERB_NAME_PL);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cad-servidores-index">

    <?php //echo Json::encode($models) 
    ?>

    <?php
    // if (1 == 2)
    // $user_recadastro = User::find()->all();
    // echo Json::encode($user_recadastro);
    foreach ($models as $recadastro) {
        $model = CadServidores::find()->where(['id' => $recadastro->id_cad_servidores])->one();
        $script = "update servidores set ";
        $script .= "IDSERVIDOR = '$model->matricula', ";
        $script .= "SERVIDOR = '$model->nome', ";
        $d_admissao = str_replace("/", ".", $model->d_admissao);
        if (!$model->d_admissao == null) $script .= "D_ADMISSAO = '$d_admissao', ";
        $nascimento_d = str_replace("/", ".", $model->nascimento_d);
        if (!$model->nascimento_d == null) $script .= "D_NASCIMENTO = '$nascimento_d', ";
        $script .= "PAI = '$model->pai', ";
        $script .= "MAE = '$model->mae', ";
        $cpf = AppController::setCpfCnpjMask($model->cpf);
        $script .= "CPF = '$cpf', ";
        $script .= "RG = '$model->rg', ";
        $script .= "RGUF = '$model->rg_uf', ";
        $script .= "RGORGAO = '$model->rg_emissor', ";
        $rg_d = str_replace("/", ".", $model->rg_d);
        if (!$model->rg_d == null) $script .= "D_RG = '$rg_d', ";
        $script .= "PISPASEP = '$model->pispasep', ";
        $pispasep_d = str_replace("/", ".", $model->pispasep_d);
        if (!$model->pispasep_d == null) $script .= "D_PISPASEP = '$pispasep_d', ";
        $script .= "CEP = '$model->cep', ";
        $script .= "ENDERECO = '$model->logradouro', ";
        $script .= "NUMERO = '$model->numero', ";
        $script .= "COMPLEMENTO = '$model->complemento', ";
        $script .= "BAIRRO = '$model->bairro', ";
        $script .= "CIDADE = '$model->cidade', ";
        $script .= "UF = '$model->uf', ";
        $script .= "TITULO = '$model->titulo', ";
        $script .= "TITULOSECAO = '$model->titulosecao', ";
        $script .= "TITULOZONA = '$model->titulozona', ";
        $script .= "NATURALIDADE = '$model->naturalidade', ";
        $script .= "NATURALIDADEUF = '$model->naturalidade_uf', ";
        $telefone = substr($model->telefone, 4);
        $telefone_ddd = substr($model->telefone, 1, 2);
        $script .= "DDD_FONE = '$telefone_ddd', ";
        $script .= "FONE = '$telefone', ";
        $celular = substr($model->celular, 4);
        $celular_ddd = substr($model->celular, 1, 2);
        $script .= "DDD_CELULAR = '$celular_ddd', ";
        $script .= "CELULAR = '$celular', ";
        $script .= "EMAIL = '$model->email', ";
        $script .= "IDBANCO = '$model->idbanco', ";
        $script .= "BANCO_AGENCIA = '$model->banco_agencia', ";
        $script .= "BANCO_AGENCIA_DIGITO = '$model->banco_agencia_digito', ";
        $script .= "BANCO_CONTA = '$model->banco_conta', ";
        $script .= "BANCO_CONTA_DIGITO = '$model->banco_conta_digito', ";
        $script .= "BANCO_OPERACAO = '$model->banco_operacao', ";
        $script .= "CARTEIRA_PROFISSIONAL = '$model->ctps', ";
        $script .= "CARTEIRA_SERIE = '$model->ctps_serie', ";
        $script .= "CARTEIRA_UF = '$model->ctps_uf', ";
        $ctps_d = str_replace("/", ".", $model->ctps_d);
        if (!$model->ctps_d == null) $script .= "D_CARTEIRA = '$ctps_d', ";
        $script .= "NACIONALIDADE = '$model->nacionalidade', ";
        $script .= "SEXO = '$model->sexo', ";
        $script .= "RACA = '$model->raca', ";
        $script .= "ESTADO_CIVIL = '$model->estado_civil', ";
        // $script .= "N_CARGA_HORARIA = '$model->n_carga_horaria', ";
        // $script .= "IDLOCAL_TRABALHO = '$model->idlocal_trabalho', ";
        if (1 == 2) {
            // Não identifiquei no sistema desktop
            $certidao = CadScertidao::find()->where(['id_cad_servidores' => $model->id])->one();
            if (!$certidao == null) {
                $script .= "TIPO_CERTIDAO = '$model->tipo', ";
                $script .= "DT_EMISSAOCERTIDAO = '$model->emissao', ";
                $script .= "TERMO_MATRICULA_CERTIDAO = '$model->termo', ";
                $script .= "LIVRO_CERTIDAO = '$model->livro', ";
                $script .= "FOLHA_CERTIDAO = '$model->folha', ";
                $script .= "CARTORIO_CERTIDAO = '$model->cartorio', ";
                $script .= "UF_CERTIDAO = '$model->uf', ";
                $script .= "MUNICIPIO_CERTIDAO = '$model->cidade', ";
            }
        }
        // $script .= "D_TEMPO = '$model->d_tempo', ";
        // $script .= "D_BENEFICIO = '$model->d_beneficio', ";
        // $script .= "D_TEMPOFIM = '$model->d_tempofim', ";
        // $script .= "VINCULOPRINCIPAL = '$model->vinculoprincipal', ";
        // $script .= "D_ULTIMASFERIAS = '$model->d_ultimasferias', ";
        // $script .= "N_VALORBASEINSS = '$model->n_valorbaseinss', ";
        // $script .= "INSALUBRIDADE_PERICULOSIDADE = '$model->insalubridade_periculosidade', ";
        // $script .= "ESCOLARIDADERAIS = '$model->escolaridaderais', ";
        // $script .= "D_LAUDOMOLESTIA = '$model->d_laudomolestia', ";
        // $script .= "MOLESTIA = '$model->molestia', ";
        // $script .= "MANAD_TIPONOMEACAO = '$model->manad_tiponomeacao', ";
        // $script .= "MANAD_NUMERONOMEACAO = '$model->manad_numeronomeacao', ";
        $script .= "CHECK_RECADASTRO = 'SIM', ";
        $d_recadastro = gmdate("d.m.Y", $recadastro->created_at);
        $script .= "DT_RECADASTRO = '$d_recadastro', ";
        $script .= "USUARIO_RECADASTRO = '$recadastro->id_user_recadastro', ";
        $updated_at = time();
        $script .= "UPDATED_AT = '$updated_at'";
        $script = str_replace("D'A", "D''A", AppController::remover_caracter($script));
        // $matricula = "";
        // foreach ($model as $key => $value) {
        //     $script .= "$key = '$value', ";
        //     if ($key == 'matricula') $matricula = $value;
        // }
        // $script = substr($script, 0, strlen($script) - 1);
        echo "$script where idservidor = '$model->matricula';" . "<br>";
    }
    ?>

</div>