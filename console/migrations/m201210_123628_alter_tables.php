<?php

use yii\db\Migration;

/**
 * Class m201210_123628_alter_tables
 */
class m201210_123628_alter_tables extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        echo "*** Alterações em diversas tabelas ***";
        $this->execute('ALTER TABLE cad_bancos DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_bconvenios DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_cargos DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_centros DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_classes DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_departamentos DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_funcoes DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_localtrabalho DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_pccs DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_scertidao DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_sdependentes DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_servidores DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_sferias DROP COLUMN slug, DROP INDEX slug');
        // $this->execute('ALTER TABLE cad_sfuncional DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_smovimentacao DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE cad_srecadastro DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_basefixa DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_basefixaeventos DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_basefixarefer DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_eventos DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_eventosbase DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_eventosdesconto DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_eventospercentual DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_faixas DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_parametros DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_referencias DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE fin_rubricas DROP COLUMN slug, DROP COLUMN AUTOMATICA,DROP COLUMN TIPO,DROP COLUMN D_DATA,DROP COLUMN I_ANOS,DROP COLUMN I_DEP_IN,DROP COLUMN I_DEP_RP,DROP COLUMN I_DEP_IR,DROP COLUMN I_DIAS,DROP COLUMN I_PRIORIDADE,DROP COLUMN I_ANOST');
        $this->execute('ALTER TABLE fin_rubricas CHANGE parcela complementar VARCHAR(3) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT "parcela"');
        $this->execute('ALTER TABLE fin_sfuncional DROP COLUMN slug, DROP COLUMN lanca_anuenio, DROP COLUMN lanca_trienio, DROP COLUMN lanca_quinquenio, DROP COLUMN lanca_decenio, DROP COLUMN D_SITUACAO, DROP COLUMN IDFUNCAO, DROP COLUMN N_DESCONTOS, DROP COLUMN N_LIQUIDO, DROP COLUMN GERADO, DROP COLUMN N_MATERNIDADE, DROP COLUMN HOLERITE_ONLINE, DROP COLUMN PONTO, DROP COLUMN lanca_salario, DROP COLUMN lanca_funcao, DROP COLUMN n_bruto, DROP COLUMN decimo_aniv, DROP COLUMN previdencia, DROP COLUMN d_tempo, DROP COLUMN d_tempofim');
        $this->execute('ALTER TABLE local_params DROP COLUMN slug, DROP INDEX slug');
        $this->execute('ALTER TABLE orgao DROP COLUMN slug, DROP INDEX slug');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180725_205204_cad_sferias cannot be reverted.\n";

        return false;
    }

    /**
     * Executa o update na tabela para inserir dados em campos vazios
     */
    public function getTableExist($table)
    {
        try {
            $q = Yii::$app->db->createCommand(
                "SELECT COUNT(*) AS quant FROM information_schema.tables "
                    . "WHERE table_name = '$table' AND TABLE_SCHEMA = 'mgfolha_import'"
            )
                ->queryOne();
            $q = $q['quant'];
        } catch (Exception $ex) {
            echo $ex;
            exit;
        }
        return $q > 0;
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
