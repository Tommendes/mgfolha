<?php

use yii\db\Migration;

/**
 * Class m190524_192113_last_upds
 */
class m190524_192113_last_upds extends Migration
{

    const TABELA_FIN_EVTS = 'fin_eventos';
    const TABELA_FIN_SFUNCIONAL = 'fin_sfuncional';
    const TABELA_CADASTROS = 'cad_servidores';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('UPDATE ' . $this::TABELA_FIN_EVTS . ' SET tipo = 0 WHERE tipo = "C"');
        $this->execute('UPDATE ' . $this::TABELA_FIN_EVTS . ' SET tipo = 1 WHERE tipo = "D"');
        $this->execute('UPDATE ' . $this::TABELA_CADASTROS . ' SET numero = "0" WHERE numero IS NULL OR LENGTH(TRIM(numero)) = 0');
        $this->execute('UPDATE ' . $this::TABELA_CADASTROS . ' SET raca = 0 WHERE raca IS NULL OR LENGTH(TRIM(raca)) = 0');
        $this->execute('UPDATE ' . $this::TABELA_CADASTROS . ' SET estado_civil = 0 WHERE estado_civil IS NULL OR LENGTH(TRIM(estado_civil)) = 0');
        $this->execute('UPDATE ' . $this::TABELA_CADASTROS . ' SET tipodeficiencia = 0 WHERE tipodeficiencia IS NULL OR LENGTH(TRIM(tipodeficiencia)) = 0');
        $this->execute('UPDATE ' . $this::TABELA_FIN_SFUNCIONAL . ' SET enio = '
            . 'IF(lanca_anuenio, 1, IF(lanca_trienio, 3, IF(lanca_quinquenio, 5, IF(lanca_decenio, 10, 0))))');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190524_192113_last_upds cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190524_192113_last_upds cannot be reverted.\n";

      return false;
      }
     */
}
