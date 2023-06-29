<?php

use yii\db\Migration;

/**
 * Class m180724_195453_orgao
 */
class m180724_195453_orgao extends Migration {

    const TABELA = 'orgao';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->dropTable("orgao_ua");
        $this->dropTable("orgao_resp");
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
            'created_at' => $this->string(255)->notNull()->comment('Registro em'),
            'updated_at' => $this->string(255)->notNull()->comment('Atualização em'),
            'orgao_cliente' => $this->string(255)->notNull()->comment('Nome orgão snake_case'),
            'orgao' => $this->string(255)->notNull()->comment('Nome do cliente'),
            'cnpj' => $this->string(14)->notNull()->comment('CNPJ do cliente'),
            'url_logo' => $this->string(255)->comment('Url da logo do cliente'),
            'cep' => $this->string(8)->notNull()->comment('Cep'),
            'logradouro' => $this->string(255)->notNull()->comment('Logradouro'),
            'numero' => $this->string(10)->notNull()->comment('Número'),
            'complemento' => $this->string(40)->comment('Complemento'),
            'bairro' => $this->string(255)->comment('Bairro'),
            'cidade' => $this->string(255)->notNull()->comment('Cidade'),
            'uf' => $this->string(2)->notNull()->comment('Estado')->defaultValue("AL"),
            'email' => $this->string(255)->comment('Email'),
            'telefone' => $this->string(14)->comment('Telefone'),
            'codigo_fpas' => $this->string(255)->comment('Código FPAS'),
            'codigo_gps' => $this->string(255)->comment('Código GPS'),
            'codigo_cnae' => $this->string(255)->comment('Código CNAE'),
            'codigo_ibge' => $this->string(255)->comment('Código IBGE'),
            'codigo_fgts' => $this->string(500)->comment('Código FGTS'),
            'mes_descsindical' => $this->string(2)->comment('Mês desconto sindical'),
            'cpf_responsavel_dirf' => $this->string(255)->comment('CPF Resp Dirf'),
            'nome_responsavel_dirf' => $this->string(255)->comment('Nome Resp Dirf'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dominio` '
                . '(`dominio`)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180724_195453_' . $this::TABELA . ' cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180724_195453_' . $this::TABELA . ' cannot be reverted.\n";

      return false;
      }
     */
}
