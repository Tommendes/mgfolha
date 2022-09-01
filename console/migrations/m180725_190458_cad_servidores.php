<?php

use yii\db\Migration;

/**
 * Class m180725_190458_cad_servidores
 */
class m180725_190458_cad_servidores extends Migration {

    const TABELA = 'cad_servidores';
    const TABELA_RUBRICAS = 'fin_rubricas';
    const TABELA_SFUNCIONAL = 'fin_sfuncional';

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
//        $this->dropIndex("fk-".$this::TABELA_RUBRICAS."-id_cad_servidores-cad_servidores-id", $this::TABELA_RUBRICAS);
//        $this->dropIndex("fk-".$this::TABELA_SFUNCIONAL."-id_cad_servidores-cad_servidores-id", $this::TABELA_SFUNCIONAL);
        $this->dropTable("cad_sdependentes");
        $this->dropTable("cad_sfuncional");
        $this->dropTable("cad_scertidao");
        $this->dropTable("cad_srecadastro");
        $this->dropTable("cad_sferias");
        $this->dropTable("cad_smovimentacao");
//        $this->dropTable("financeiro");
        $this->dropTable("fin_rubricas");
//        $this->dropTable("mensal");
        $this->dropTable("fin_sfuncional");
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
            'url_foto' => $this->string(255)->comment('Foto do servidor'),
            'matricula' => $this->integer(11)->notNull()->comment('Matricula'),
            'nome' => $this->string(255)->notNull()->comment('Nome'),
            'cpf' => $this->string(11)->comment('CPF'),
            'rg' => $this->string(20)->comment('RG'),
            'rg_emissor' => $this->string(20)->comment('RG emissor'),
            'rg_uf' => $this->string(2)->comment('RG UF'),
            'rg_d' => $this->string(10)->comment('RG emissão'),
            'pispasep' => $this->string(14)->comment('PIS'),
            'pispasep_d' => $this->string(10)->comment('PIS emissão'),
            'titulo' => $this->string(20)->comment('Título'),
            'titulosecao' => $this->string(4)->comment('Tit sessão'),
            'titulozona' => $this->string(4)->comment('Tit zona'),
            'ctps' => $this->string(7)->comment('CTPS'),
            'ctps_serie' => $this->string(5)->comment('CTPS série'),
            'ctps_uf' => $this->string(2)->comment('CTPS UF'),
            'ctps_d' => $this->string(10)->comment('CTPS emissão'),
            'nascimento_d' => $this->string(10)->comment('Nascimento'),
            'pai' => $this->string(100)->comment('Pai'),
            'mae' => $this->string(100)->comment('Mãe'),
            'cep' => $this->string(8)->notNull()->comment('Cep')->defaultValue("00000000"),
            'logradouro' => $this->string(255)->notNull()->comment('Logradouro'),
            'numero' => $this->string(10)->notNull()->comment('Número')->defaultValue("0"),
            'complemento' => $this->string(40)->comment('Complemento'),
            'bairro' => $this->string(255)->comment('Bairro'),
            'cidade' => $this->string(255)->notNull()->comment('Cidade'),
            'uf' => $this->string(2)->notNull()->comment('Estado')->defaultValue("AL"),
            'naturalidade' => $this->string(255)->comment('Naturalidade'),
            'naturalidade_uf' => $this->string(2)->comment('Nat UF'),
            'telefone' => $this->string(14)->comment('Telefone'),
            'celular' => $this->string(255)->comment('Celular'),
            'email' => $this->string(255)->comment('Email'),
            'idbanco' => $this->integer(11)->comment('Banco'),
            'banco_agencia' => $this->string(5)->comment('Agência'),
            'banco_agencia_digito' => $this->string(1)->comment('Ag dígito'),
            'banco_conta' => $this->string(12)->comment('Conta'),
            'banco_conta_digito' => $this->string(1)->comment('Cta dígito'),
            'banco_operacao' => $this->string(5)->comment('Cta Operação'),
            'nacionalidade' => $this->string(2)->comment('Nacionalidade'),
            'sexo' => $this->tinyInteger()->comment('Sexo'),
            'raca' => $this->tinyInteger()->comment('Raça'),
            'estado_civil' => $this->tinyInteger()->comment('Estado civil'),
            'tipodeficiencia' => $this->tinyInteger()->comment('Tipo deficiência'),
            'd_admissao' => $this->string(10)->notNull()->comment('Admissão'),
                ], $tableOptions);

        $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dominio_matricula` '
                . '(dominio, matricula)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m180725_190458_cad_servidores cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180725_190458_cad_servidores cannot be reverted.\n";

      return false;
      }
     */
}
