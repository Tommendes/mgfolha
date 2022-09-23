<?php

use yii\db\Migration;

/**
 * Class m180828_131106_fin_eventos
 */
class m180828_131106_fin_eventos extends Migration {

    const TABELA = 'fin_eventos';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable('fin_eventosbase');
        $this->dropTable('fin_basefixarefer');
        $this->dropTable('fin_basefixaeventos');
        $this->dropTable('fin_basefixa');
        $this->dropTable('fin_eventosdesconto');
        $this->dropTable('fin_eventospercentual');
        $this->dropTable($this::TABELA);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  ENGINE=InnoDB';
        }

        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'ev_root' => $this->integer(11)->notNull()->comment('Pertence ao sistema'),
            'id_evento' => $this->string(3)->notNull()->comment('Codigo evento'),
            'evento_nome' => $this->string(255)->notNull()->comment('Evento'),
            'tipo' => $this->char(1)->notNull()->comment('Tipo'),
            'consignado' => $this->tinyInteger(1)->notNull()->comment('Consignado'),
            'consignavel' => $this->tinyInteger(1)->notNull()->comment('Consignável'),
            'deduzconsig' => $this->tinyInteger(1)->notNull()->comment('Deduz da consignação'),
            'automatico' => $this->tinyInteger(1)->notNull()->comment('Automático'),
            'vinculacao_dirf' => $this->string(5)->comment('DIRF'),
            'i_prioridade' => $this->tinyInteger(1)->notNull()->comment('Prioridade'),
            'fixo' => $this->tinyInteger(1)->notNull()->comment('Fixo'),
            'sefip' => $this->tinyInteger(1)->comment('SEFIP'),
            'rais' => $this->tinyInteger(1)->comment('RAIS'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-id_evento` '
                . '(dominio, id_evento)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_131106_fin_eventos cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_131106_fin_eventos cannot be reverted.\n";

      return false;
      }
     */
}
