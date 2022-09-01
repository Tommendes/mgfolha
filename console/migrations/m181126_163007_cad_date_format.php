<?php

use yii\db\Migration;

/**
 * Class m181126_163007_cad_date_format
 */
class m181126_163007_cad_date_format extends Migration
{

    const TABELA_SERVIDORES = 'cad_servidores';
    const TABELA_CERTIDAO = 'cad_scertidao';
    const TABELA_DEPENDENTES = 'cad_sdependentes';
    const TABELA_FUNCIONAL = 'cad_sfuncional';
    const TABELA_FERIAS = 'cad_sferias';
    const TABELA_MOVIMENTACAO = 'cad_smovimentacao';
    const TABELA_FAIXAS = 'fin_faixas';
    const TABELA_PARAMETROS = 'fin_parametros';
    const TABELA_REFERENCIAS = 'fin_referencias';
    const TABELA_BASEFIXAREFER = 'fin_basefixarefer';
    const TABELA_EVENTOSPERCENTUAL = 'fin_eventospercentual';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo "Todos os bancos de dados desse ciente já foram importados?  Escreva Sim[S] para continuar: ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (1 == 1) {
            if (trim(strtolower($line)) === 'sim' || trim(strtolower($line)) === 's') {
                $this->execute('UPDATE ' . $this::TABELA_SERVIDORES . ' set rg_d = DATE_FORMAT(rg_d, "%d/%m/%Y") WHERE LENGTH(rg_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_SERVIDORES . ' set pispasep_d = DATE_FORMAT(pispasep_d, "%d/%m/%Y") WHERE LENGTH(pispasep_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_SERVIDORES . ' set ctps_d = DATE_FORMAT(ctps_d, "%d/%m/%Y") WHERE LENGTH(ctps_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_SERVIDORES . ' set nascimento_d = DATE_FORMAT(nascimento_d, "%d/%m/%Y") WHERE LENGTH(nascimento_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_SERVIDORES . ' set d_admissao = DATE_FORMAT(d_admissao, "%d/%m/%Y") WHERE LENGTH(d_admissao) = 10');
                $this->execute('UPDATE ' . $this::TABELA_FUNCIONAL . ' set d_laudomolestia = DATE_FORMAT(d_laudomolestia, "%d/%m/%Y") WHERE LENGTH(d_laudomolestia) = 10');
                $this->execute('UPDATE ' . $this::TABELA_FUNCIONAL . ' set d_tempo = DATE_FORMAT(d_tempo, "%d/%m/%Y") WHERE LENGTH(d_tempo) = 10');
                $this->execute('UPDATE ' . $this::TABELA_FUNCIONAL . ' set d_tempofim = DATE_FORMAT(d_tempofim, "%d/%m/%Y") WHERE LENGTH(d_tempofim) = 10');
                $this->execute('UPDATE ' . $this::TABELA_FUNCIONAL . ' set d_beneficio = DATE_FORMAT(d_beneficio, "%d/%m/%Y") WHERE LENGTH(d_beneficio) = 10');
                $this->execute('UPDATE ' . $this::TABELA_FERIAS . ' set d_ferias = DATE_FORMAT(d_ferias, "%d/%m/%Y") WHERE LENGTH(d_ferias) = 10');
                $this->execute('UPDATE ' . $this::TABELA_CERTIDAO . ' set emissao = DATE_FORMAT(emissao, "%d/%m/%Y") WHERE LENGTH(emissao) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set certidao_d = DATE_FORMAT(certidao_d, "%d/%m/%Y") WHERE LENGTH(certidao_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set nascimento_d = DATE_FORMAT(nascimento_d, "%d/%m/%Y") WHERE LENGTH(nascimento_d) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set inss_prz = DATE_FORMAT(inss_prz, "%d/%m/%Y") WHERE LENGTH(inss_prz) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set irrf_prz = DATE_FORMAT(irrf_prz, "%d/%m/%Y") WHERE LENGTH(irrf_prz) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set rpps_prz = DATE_FORMAT(rpps_prz, "%d/%m/%Y") WHERE LENGTH(rpps_prz) = 10');
                $this->execute('UPDATE ' . $this::TABELA_DEPENDENTES . ' set rg_d = DATE_FORMAT(rg_d, "%d/%m/%Y") WHERE LENGTH(rg_d) = 10');
                // Normalmente usado apenas para ativos e câmaras  
                $this->execute('UPDATE ' . $this::TABELA_MOVIMENTACAO . ' SET d_afastamento = DATE_FORMAT(d_afastamento, "%d/%m/%Y") WHERE LENGTH(d_afastamento) = 10');
                $this->execute('UPDATE ' . $this::TABELA_MOVIMENTACAO . ' SET d_retorno = DATE_FORMAT(d_retorno, "%d/%m/%Y") WHERE LENGTH(d_retorno) = 10');
                $this->execute('UPDATE ' . $this::TABELA_MOVIMENTACAO . ' SET d_afastamento = date_format(DATE_SUB(STR_TO_DATE(d_afastamento, "%d/%m/%Y"), INTERVAL 1 DAY),"%d/%m/%Y") where codigo_afastamento not in("Q1", "x", "p1")');
                $this->execute('UPDATE ' . $this::TABELA_FAIXAS . ' SET data = DATE_FORMAT(data, "%d/%m/%Y") WHERE LENGTH(data) = 10');
                $this->execute('UPDATE ' . $this::TABELA_PARAMETROS . ' SET d_situacao = DATE_FORMAT(d_situacao, "%d/%m/%Y") WHERE LENGTH(d_situacao) = 10');
                $this->execute('UPDATE ' . $this::TABELA_REFERENCIAS . ' SET data = DATE_FORMAT(data, "%d/%m/%Y") WHERE LENGTH(data) = 10');
                $this->execute('UPDATE ' . $this::TABELA_BASEFIXAREFER . ' SET data = DATE_FORMAT(data, "%d/%m/%Y") WHERE LENGTH(data) = 10');
                $this->execute('UPDATE ' . $this::TABELA_EVENTOSPERCENTUAL . ' SET data = DATE_FORMAT(data, "%d/%m/%Y") WHERE LENGTH(data) = 10');
                fclose($handle);
            } else {
                echo "\n";
                echo "Operação abortada...\n";
                return false;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180725_205204_cad_sferias cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_205204_cad_sferias cannot be reverted.\n";

      return false;
      }
     */
}
