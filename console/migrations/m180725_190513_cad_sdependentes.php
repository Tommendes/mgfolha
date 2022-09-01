<?php

use yii\db\Migration;

/**
 * Class m180725_190513_cad_dependentes
 */
class m180725_190513_cad_sdependentes extends Migration {

    const TABELA = 'cad_sdependentes';

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
            'matricula' => $this->integer(11)->notNull()->comment('Matricula dependente'),
            'nome' => $this->string(255)->notNull()->comment('Nome'),
            'tipo' => $this->tinyInteger()->notNull()->comment('Tipo'),
            'permanente' => $this->tinyInteger()->notNull()->comment('Permanente'),
            'nascimento_d' => $this->string(10)->comment('Nascimento data'),
            'inss_prz' => $this->string(10)->comment('Prazo INSS'),
            'irrf_prz' => $this->string(10)->comment('Prazo IRRF'),
            'rpps_prz' => $this->string(10)->comment('Prazo RPPS'),
            'rg' => $this->string(20)->comment('RG'),
            'rg_emissor' => $this->string(20)->comment('RG emissor'),
            'rg_uf' => $this->string(2)->comment('RG UF'),
            'rg_d' => $this->string(10)->comment('RG emissão'),
            'certidao' => $this->string(255)->comment('Certidão'),
            'livro' => $this->string(255)->comment('Livro'),
            'folha' => $this->string(255)->comment('Folha'),
            'certidao_d' => $this->string(255)->comment('Emissão'),
            'carteira_vacinacao' => $this->tinyInteger()->comment('Cartão de vacinação'),
            'historico_escolar' => $this->tinyInteger()->comment('Histórico escolar'),
            'plano_saude' => $this->tinyInteger()->comment('Plano de saúde'),
            'cpf' => $this->string(14)->comment('CPF'),
            'relacao_dependecia' => $this->string(8)->comment('Relação de dependência'),
            'pensionista' => $this->tinyInteger()->comment('Pensionista'),
            'irpf' => $this->tinyInteger()->comment('Dependente IRRF'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '-id_cad_servidores-matricula-dominio` '
                . '(id_cad_servidores, matricula, dominio)');

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_cad_servidores-cad_servidores-id` '
                . 'FOREIGN KEY (id_cad_servidores) REFERENCES cad_servidores(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180725_190513_cad_dependentes cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_190513_cad_dependentes cannot be reverted.\n";

      return false;
      }
     */
}
