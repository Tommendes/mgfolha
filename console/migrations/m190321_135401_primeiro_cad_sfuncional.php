<?php

use yii\db\Migration;

/**
 * Class m190321_135401_primeiro_cad_sfuncional
 */
class m190321_135401_primeiro_cad_sfuncional extends Migration {  

    const TABELA = 'cad_sfuncional';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->execute('INSERT INTO ' . $this::TABELA . ' ( '
                . 'id, slug, STATUS, dominio, evento, created_at, updated_at, '
                . 'ano, mes, complementar, id_cad_servidores, id_local_trabalho, '
                . 'id_cad_principal, id_escolaridade, escolaridaderais, rais, '
                . 'dirf, sefip, sicap, insalubridade, decimo, id_vinculo, '
                . 'id_cat_sefip, ocorrencia, carga_horaria, molestia, '
                . 'd_laudomolestia, manad_tiponomeacao, manad_numeronomeacao, '
                . 'd_admissao, d_tempo, d_tempofim, d_beneficio, n_valorbaseinss) ('
                . 'SELECT NULL, SHA(id), STATUS, dominio, evento, '
                . 'UNIX_TIMESTAMP(CURRENT_DATE), UNIX_TIMESTAMP(CURRENT_DATE), '
                . 'YEAR(CURRENT_DATE), LPAD(MONTH(CURRENT_DATE),2,"0"), "000", '
                . 'id_cad_servidores, id_local_trabalho, id_cad_principal, '
                . 'id_escolaridade, escolaridaderais, rais, dirf, sefip, sicap, '
                . 'insalubridade, decimo, id_vinculo, id_cat_sefip, ocorrencia, '
                . 'carga_horaria, molestia, d_laudomolestia, manad_tiponomeacao, '
                . 'manad_numeronomeacao, d_admissao, d_tempo, d_tempofim, '
                . 'd_beneficio, n_valorbaseinss FROM ' . $this::TABELA . ')');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m190321_135401_primeiro_cad_sfuncional cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m190321_135401_primeiro_cad_sfuncional cannot be reverted.\n";

      return false;
      }
     */
}
