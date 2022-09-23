<?php

use yii\db\Migration;

/**
 * Class m180828_203204_fin_rubricas
 */
class m180828_203204_fin_rubricas extends Migration
{

    const TABELA_V3 = 'financeiro';
    const TABELA = 'fin_rubricas';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8mb4-general-ci-and-utf8mb4-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  ENGINE=InnoDB';
        }

        // Renomeia a tabela v3 -> v4 e faz algumas alterações na estrutura
        if ($this->getTableExist($this::TABELA_V3)) {
            // Exclui a tabela v4
            $this->dropTable($this::TABELA);
            $this->renameTable($this::TABELA_V3, $this::TABELA);
            // Primeira alteração na tabela fin_sfuncional
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD COLUMN `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)');
            $this->execute('ALTER TABLE ' . $this::TABELA
                . ' ADD COLUMN slug VARCHAR(255) NOT NULL AFTER id, '
                . 'ADD COLUMN status TINYINT(3) DEFAULT 10 NOT NULL AFTER id, '
                . 'ADD column dominio VARCHAR(255) NOT NULL COMMENT "Domínio do cliente" AFTER status, '
                . 'ADD COLUMN evento INT(11) NOT NULL DEFAULT 1 AFTER dominio, '
                . 'ADD COLUMN created_at INT(11) NOT NULL COMMENT "Registro em" AFTER evento, '
                . 'ADD COLUMN updated_at INT(11) NOT NULL COMMENT "Atualização em" AFTER created_at, '
                . 'ADD COLUMN id_cad_servidores INT(11) NULL COMMENT "Servidor" AFTER updated_at, '
                . 'ADD COLUMN id_fin_eventos INT(11) NULL COMMENT "Rúbrica" AFTER id_cad_servidores, '
                . 'ADD COLUMN id_con_contratos INT(11) UNSIGNED NULL AFTER id_fin_eventos, '
                . 'CHANGE ANO ano VARCHAR(4) NOT NULL COMMENT "Ano", '
                . 'CHANGE MES mes VARCHAR(2) NOT NULL COMMENT "Mês" AFTER ano, '
                . 'CHANGE PARCELA parcela VARCHAR(3) NOT NULL COMMENT "parcela" AFTER mes, '
                . 'CHANGE REFERENCIA referencia VARCHAR(255) NULL COMMENT "Referência" AFTER parcela, '
                . 'CHANGE N_BASEESPECIAL valor_baseespecial FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Valor base especial" AFTER referencia, '
                . 'CHANGE N_BASE valor_base FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Valor base" AFTER valor_baseespecial, '
                . 'CHANGE N_BASEFIXA valor_basefixa FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Valor base fixa" AFTER valor_base, '
                . 'CHANGE N_DESCONTO valor_desconto FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Desconto" AFTER valor_basefixa, '
                . 'CHANGE N_PERCENTUAL valor_percentual FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Percentual" AFTER valor_desconto, '
                . 'CHANGE N_SALDO valor_saldo FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Saldo" AFTER valor_percentual, '
                . 'CHANGE N_VALOR valor FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Provento|Desconto" AFTER valor_saldo, '
                . 'CHANGE N_VALORPATRONAL valor_patronal FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Valor patronal" AFTER valor, '
                . 'CHANGE N_VALOR_MATERNIDADE valor_maternidade FLOAT(11,2) DEFAULT 0 NOT NULL COMMENT "Valor maternidade" AFTER valor_patronal, '
                . 'CHANGE I_PRAZO prazo INT(3) DEFAULT 1 NOT NULL COMMENT "Parcela atual" AFTER valor_maternidade, '
                . 'CHANGE I_PRAZOT prazot INT(3) DEFAULT 1 NOT NULL COMMENT "Parcela final" AFTER prazo '
                // . ', ADD PRIMARY KEY (id)'
            );
        } else {
            echo '*** Tabela "' . $this::TABELA_V3 . '" não existe. Talvez ela já tenha sido renomeada. ***';
            echo "\nDeseja continuar? Escreva Sim[S] para continuar: ";
            $handle = fopen("php://stdin", "r");
            $line = fgets($handle);
            if (trim(strtolower($line)) === 'nao' || trim(strtolower($line)) === 'n') {
                exit;
            }
        }

        if ($this->getTableExist($this::TABELA)) {
            // Atualiza dados na tabela
            $this->setUpdateTable();

            echo "*** Excluir índices ***";
            // $this->dropIndex('`' . $this::TABELA . '_slug_unique`', $this::TABELA);
            $this->dropIndex('`' . $this::TABELA . '_dom-serv-evtos-ano-mes-parcela`', $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-id_fin_eventos-fin_eventos-id`", $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-id_cad_servidores-cad_servidores-id`", $this::TABELA);

            echo "*** Recriar índices ***";
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-serv-evtos-ano-mes-parcela` '
                . '(dominio, id_cad_servidores, id_fin_eventos, ano, mes, parcela)');
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_fin_eventos-fin_eventos-id` '
                . 'FOREIGN KEY (id_fin_eventos) REFERENCES fin_eventos(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-id_cad_servidores-cad_servidores-id`'
                . 'FOREIGN KEY (id_cad_servidores) REFERENCES cad_servidores(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');

            echo "*** Excluir colunas obsoletas ***";
            $this->execute('ALTER TABLE ' . $this::TABELA
                . ' DROP COLUMN IDSERVIDOR, DROP COLUMN IDEVENTO');
        } else {
            echo '*** Tabela "' . $this::TABELA . '" não existe ***';
            exit;
        }
    }

    /**
     * Executa o update na tabela para inserir dados em campos vazios
     */
    public function getTableExist($table)
    {
        try {
            $q = Yii::$app->db->createCommand(
                "SELECT COUNT(*) AS quant FROM information_schema.tables "
                    . "WHERE table_name = '$table'"
            )
                ->queryOne();
            $q = $q['quant'];
        } catch (Exception $ex) {
            echo $ex;
            exit;
        }
        return $q > 0;
    }

    /**
     * Executa o update na tabela para inserir dados em campos vazios
     */
    public function setUpdateTable()
    {
        $salto = 250;
        $q = Yii::$app->db->createCommand(
            'select count(id) as quant from ' . $this::TABELA
        )
            ->queryOne();
        $quant = $q['quant'];
        $x = 0;
        do {
            $this->execute('UPDATE ' . $this::TABELA . ' f '
                . 'JOIN cad_servidores AS cs ON CAST(cs.matricula AS INTEGER) = CAST(f.idservidor AS INTEGER) '
                . 'JOIN fin_eventos AS ev ON CAST(ev.id_evento AS INTEGER) = CAST(f.idevento AS INTEGER) '
                . 'SET f.slug = SHA(f.id), '
                . 'f.created_at = UNIX_TIMESTAMP(NOW()), ' // 
                . 'f.updated_at = UNIX_TIMESTAMP(NOW()), '
                . 'f.id_cad_servidores = cs.id, f.id_fin_eventos = ev.id '
                . 'WHERE f.id between ' . ($x + 1) . ' and ' . ($x + $salto));
            $x = $x + $salto;
            $quant = $quant - $salto;
            echo $quant;
        } while ($quant > 0);
        // echo 'Setar Slug';
        // $this->execute('UPDATE ' . $this::TABELA . ' f SET f.slug = SHA(f.id)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180828_203204_fin_rubricas cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180828_203204_fin_rubricas cannot be reverted.\n";

      return false;
      }
     */
}
