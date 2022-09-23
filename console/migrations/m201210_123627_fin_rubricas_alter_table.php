<?php

use yii\db\Migration;

/**
 * Class m201210_123627_fin_rubricas_alter_table
 */
class m201210_123627_fin_rubricas_alter_table extends Migration
{

    const TABELA_RUBRICAS = 'fin_rubricas';
    // ALTER TABLE `wwmga_tapera_previdencia`.`fin_rubricas` ADD CONSTRAINT `fk-fin_rubricas-id_con_contratos-con_contratos-id` FOREIGN KEY (`id_con_contratos`) REFERENCES `wwmga_tapera_previdencia`.`con_contratos`(`id`) ON UPDATE CASCADE ON DELETE NO ACTION; 

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->getTableExist($this::TABELA_RUBRICAS)) {
            echo "*** Alterações em " . $this::TABELA_RUBRICAS . " ***";
            // $this->execute('ALTER TABLE ' . $this::TABELA_RUBRICAS . ' CHANGE parcela complementar VARCHAR(3) CHARSET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT "parcela"');
        } else {
            echo "\n";
            echo $this::TABELA_RUBRICAS . " ainda não foi criada\n";
            echo "Operação abortada...\n";
            return false;
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
