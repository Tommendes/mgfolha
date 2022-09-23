<?php

use yii\db\Migration;

/**
 * Class m180828_131142_fin_eventosdesconto
 */
class m180828_131142_fin_eventosdesconto extends Migration {

    const TABELA = 'fin_eventosdesconto';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
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
            'id_fin_eventos' => $this->integer(11)->notNull()->comment('Codigo evento'),
            'id_fin_eventosdesconto' => $this->integer(11)->notNull()->comment('Evento a descontar'),
                ], $tableOptions);
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-id_evento-id_fin_eventosdesconto` '
                . '(dominio, id_fin_eventos, id_fin_eventosdesconto)');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_evento-fin_eventos-id` '
                . 'FOREIGN KEY (id_fin_eventos) REFERENCES fin_eventos(id) '
                . 'ON UPDATE CASCADE ON DELETE CASCADE');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_fin_eventosdesconto-fin_eventos-id` '
                . 'FOREIGN KEY (id_fin_eventosdesconto) REFERENCES fin_eventos(id) '
                . 'ON UPDATE CASCADE ON DELETE CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_131142_fin_eventosdesconto cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_131142_fin_eventosdesconto cannot be reverted.\n";

      return false;
      }
     */
}
