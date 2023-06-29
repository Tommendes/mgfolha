<?php

use yii\db\Migration;

/**
 * Class m180724_201447_orgao_resp
 */
class m180724_201447_orgao_resp extends Migration {

    const TABELA = 'orgao_resp';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  ENGINE=InnoDB';
        }

        $this->dropTable($this::TABELA);

        $this->createTable($this::TABELA, [
            'id' => $this->primaryKey()->comment('ID do registro'),
            'id_orgao' => $this->integer(11)->notNull()->comment('Orgão'),
            'status' => $this->tinyInteger()->notNull()->defaultValue(10)->comment(''),
            'evento' => $this->integer(11)->notNull()->comment('Evento do registro'),
            'created_at' => $this->string(255)->notNull()->comment('Registro em'),
            'updated_at' => $this->string(255)->notNull()->comment('Atualização em'),
            'cpf_gestor' => $this->string(11)->notNull()->comment('Cpf do gestor'),
            'nome_gestor' => $this->string(255)->notNull()->comment('Nome do gestor'),
            'd_nascimento' => $this->string(10)->comment('Nascimento do gestor'),
            'cep' => $this->string(8)->notNull()->comment('Cep'),
            'logradouro' => $this->string(255)->notNull()->comment('Logradouro'),
            'numero' => $this->string(10)->notNull()->comment('Número'),
            'complemento' => $this->string(40)->comment('Complemento'),
            'bairro' => $this->string(255)->notNull()->comment('Bairro'),
            'cidade' => $this->string(255)->notNull()->comment('Cidade'),
            'uf' => $this->string(2)->notNull()->comment('Estado')->defaultValue("AL"),
            'email' => $this->string(255)->comment('Email'),
            'telefone' => $this->string(14)->comment('Telefone'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_orgao-orgao-id` '
                . 'FOREIGN KEY (id_orgao) REFERENCES orgao(id) '
                . 'ON UPDATE CASCADE ON DELETE CASCADE');
        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `un-' . $this::TABELA . '-id_orgao-cpf_gestor` '
                . '(`id_orgao`, `cpf_gestor`)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180724_201447_orgao_resp cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180724_201447_orgao_resp cannot be reverted.\n";

      return false;
      }
     */
}
