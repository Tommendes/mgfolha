<?php

use yii\db\Migration;

/**
 * Class m180724_201434_orgao_ua
 */
class m180724_201434_orgao_ua extends Migration {

    const TABELA = 'orgao_ua';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  ENGINE=InnoDB';
        }

        $this->dropTable($this::TABELA);

        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'id_orgao' => $this->integer(11)->notNull()->comment(''),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10)->comment(''),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro'),
            'created_at' => $this->integer(11)->notNull()->comment('Registro em'),
            'updated_at' => $this->integer(11)->notNull()->comment('Atualização em'),
            'codigo' => $this->string(10)->notNull()->comment('Código'),
            'cnpj' => $this->string(14)->notNull()->comment('CNPJ'),
            'nome' => $this->string(255)->notNull()->comment('Nome'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_orgao-orgao-id` '
                . 'FOREIGN KEY (id_orgao) REFERENCES orgao(id) '
                . 'ON UPDATE CASCADE ON DELETE CASCADE');
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `un-' . $this::TABELA . '-id_orgao-codigo-cnpj` '
                . '(`id_orgao`, `codigo`, `cnpj`)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180724_201434_' . $this::TABELA . ' cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180724_201434_' . $this::TABELA . ' cannot be reverted.\n";

      return false;
      }
     */
}
