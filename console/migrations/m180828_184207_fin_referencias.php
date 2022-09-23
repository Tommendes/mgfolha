<?php

use yii\db\Migration;

/**
 * Class m180828_184207_fin_referencias
 */
class m180828_184207_fin_referencias extends Migration {

    const TABELA = 'fin_referencias';

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

        // Cria a tabela
        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'id_pccs' => $this->integer(11)->notNull()->comment('PCCS'),
            'id_classe' => $this->integer(11)->notNull()->comment('Classe'),
            'referencia' => $this->integer(11)->notNull()->comment('Referência'),
            'valor' => $this->decimal(11, 2)->notNull()->comment('Valor'),
            'data' => $this->string(10)->notNull()->comment('Data'),
                ], $tableOptions);

        // Cria os indices da tabela
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-id_pccs-cad_classes-referencia` '
                . '(dominio, id_pccs, id_classe, referencia)');
//        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_pccs-cad_pccs-id_pccs`'
//                . 'FOREIGN KEY (id_pccs) REFERENCES cad_pccs(id_pccs) '
//                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
//        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_classe-cad_classes-id_classe`'
//                . 'FOREIGN KEY (id_classe) REFERENCES cad_classes(id_classe) '
//                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_184207_fin_referencias cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_184207_fin_referencias cannot be reverted.\n";

      return false;
      }
     */
}
