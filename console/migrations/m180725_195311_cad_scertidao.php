<?php

use yii\db\Migration;

/**
 * Class m180725_195311_cad_scertidao
 */
class m180725_195311_cad_scertidao extends Migration {

    const TABELA = 'cad_scertidao';

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
            'id_cad_servidores' => $this->integer(11)->notNull()->comment('ID do servidor'),
            'tipo' => $this->tinyInteger()->comment('Tipo'),
            'certidao' => $this->string(255)->comment('Certidão'),
            'emissao' => $this->string(10)->comment('Emissão'),
            'cartorio' => $this->string(255)->comment('Cartório'),
            'uf' => $this->string(2)->comment('Uf'),
            'cidade' => $this->string(255)->comment('Municipio'),
            'termo' => $this->string(255)->comment('Termo'),
            'livro' => $this->string(255)->comment('Livro'),
            'folha' => $this->string(255)->comment('Folha'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_id_cad_servidores-tipo-emissao` '
                . '(id_cad_servidores, tipo, emissao)');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_cad_servidores-cad_servidores-id` '
                . 'FOREIGN KEY (id_cad_servidores) REFERENCES cad_servidores(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180725_195311_cad_scertidao cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_195311_cad_scertidao cannot be reverted.\n";

      return false;
      }
     */
}
