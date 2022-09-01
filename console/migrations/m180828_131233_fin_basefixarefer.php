<?php

use yii\db\Migration;

/**
 * Class m180828_131233_fin_basefixarefer
 */
class m180828_131233_fin_basefixarefer extends Migration {

    const TABELA = 'fin_basefixarefer';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable($this::TABELA);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  ENGINE=InnoDB';
        }

        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'slug' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10),
            'dominio' => $this->string()->notNull()->comment('Domínio do cliente'),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro')->defaultValue(0),
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'id_fin_base_fixa' => $this->integer(11)->notNull()->comment('Codigo base fixa'),
            'id_fixa' => $this->string(4)->notNull()->comment('Codigo referencia'),
            'descricao' => $this->string()->notNull()->comment('Descrição'),
            'data' => $this->string(10)->notNull()->comment('Data'),
            'valor' => $this->decimal(11, 2)->notNull()->comment('Valor base')->defaultValue(0),
                ], $tableOptions);
                
                
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-id_fin_base_fixa-id_fixa` '
                . '(dominio, id_fin_base_fixa, id_fixa)');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_fin_base_fixa-fin_basefixa-id` '
                . 'FOREIGN KEY (id_fin_base_fixa) REFERENCES fin_basefixa(id) '
                . 'ON UPDATE CASCADE ON DELETE CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180828_131233_fin_basefixarefer cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_131233_fin_basefixarefer cannot be reverted.\n";

      return false;
      }
     */
}
