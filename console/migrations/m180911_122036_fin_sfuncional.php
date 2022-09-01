<?php

use yii\db\Migration;

/**
 * Class m180911_122036_fin_sfuncional
 */
class m180911_122036_fin_sfuncional extends Migration
{

    const TABELA_V3 = 'mensal';
    const TABELA = 'fin_sfuncional';

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

        if ($this->getTableExist($this::TABELA_V3)) {
            // Exclui a tabela v4
            $this->dropTable($this::TABELA);
            // Renomeia a tabela v3 -> v4
            $this->renameTable($this::TABELA_V3, $this::TABELA);
            // Primeira alteração na tabela fin_sfuncional
            $this->execute('ALTER TABLE ' . $this::TABELA
                . ' ADD COLUMN id INT(11) NOT NULL AUTO_INCREMENT FIRST, '
                . 'ADD COLUMN slug VARCHAR(255) NOT NULL AFTER id, '
                . 'ADD COLUMN status TINYINT(3) DEFAULT 10 NOT NULL AFTER slug, '
                . 'add column dominio VARCHAR(255) NOT NULL COMMENT "Domínio do cliente" AFTER status, '
                . 'ADD COLUMN evento INT(11) DEFAULT 0 NOT NULL AFTER dominio, '
                . 'ADD COLUMN created_at INT(11) NOT NULL COMMENT "Registro em" AFTER dominio, '
                . 'ADD COLUMN updated_at INT(11) NOT NULL COMMENT "Atualização em" AFTER created_at, '
                . 'ADD COLUMN id_cad_servidores INT(11) NOT NULL COMMENT "Servidor" AFTER updated_at, '
                . 'ADD COLUMN tp_previdencia TINYINT(1) NULL COMMENT "Tipo da previdência (2 = RPPS e 3 - INSS)" AFTER desconta_irrf, '
                . 'CHANGE ano ano VARCHAR(4) NOT NULL AFTER idservidor, '
                . 'CHANGE parcela parcela VARCHAR(3) NOT NULL AFTER mes, '
                . 'ADD COLUMN id_cad_cargos INT(11) NULL AFTER situacao, ADD COLUMN id_cad_centros INT(11) NULL AFTER idcargo, '
                . 'ADD COLUMN id_cad_departamentos INT(11) NULL AFTER idcentro, '
                . 'CHANGE idpccs id_pccs INT(11) NULL COMMENT "PCCS" AFTER id_cad_departamentos, '
                //                    . 'ADD COLUMN id_pccs INT(11) NULL AFTER idlocacao, '
                . 'CHANGE n_faltas n_faltas INT(3) NULL, '
                . 'CHANGE d_beneficio d_beneficio VARCHAR(10) NULL, '
                . 'CHANGE retorno_data retorno_data VARCHAR(10) NULL, '
                . 'CHANGE retorno_valor retorno_valor FLOAT(11,2) NULL, '
                . 'CHANGE n_horaaula n_horaaula INT(3) NULL, '
                . 'CHANGE n_adnoturno n_adnoturno INT(3) NULL, '
                . 'CHANGE n_hextra n_hextra INT(3) NULL, '
                . 'CHANGE desconta_sindicato desconta_sindicato VARCHAR(3) NULL AFTER desconta_rpps, '
                . 'ADD COLUMN enio TINYINT(1) NULL COMMENT "Anuênio, Triênio, Quinquênio ou Decênio" AFTER desconta_sindicato, '
                . 'ADD PRIMARY KEY (id)');
        } else {
            echo '*** Tabela "' . $this::TABELA_V3 . '" não existe ***';
            exit;
        }

        if ($this->getTableExist($this::TABELA)) {
            //     echo 'Setar Slug';
            //     $this->execute('UPDATE ' . $this::TABELA . ' f SET f.slug = SHA(f.id)');
            // Atualiza dados na tabela
            $this->setUpdateTable();

            echo "*** Excluir índices ***";
            //     $this->dropIndex('`' . $this::TABELA . '_slug_unique`', $this::TABELA);
            $this->dropIndex('`' . $this::TABELA . '_dom-serv-ano-mes-parcela`', $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-id_cad_servidores-cad_servidores-id`", $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-cargos-cad_cargos-id`", $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-centros-cad_centros-id`", $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-departamentos-cad_departamentos-id`", $this::TABELA);
            $this->dropIndex("`fk-" . $this::TABELA . "-pccs-cad_pccs-id`", $this::TABELA);

            echo "*** Recriar índices ***";
            //     $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_slug_unique` '
            //             . '(slug)');
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD UNIQUE INDEX `' . $this::TABELA . '_dom-serv-ano-mes-parcela` '
                . '(dominio, id_cad_servidores, ano, mes, parcela)');
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA . '-servidores-cad_servidores-id`'
                . 'FOREIGN KEY (id_cad_servidores) REFERENCES cad_servidores(id) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION');

            echo "*** Alterações adicionais na estrutura da tabela ***";
            // Segunda alteração na tabela fin_sfuncional. Esta
            // exclui as colunas tornadas obsoletas, altera o formato dos dados
            // editados e insere os comentários que se tornarão o label dos inputs
            $this->execute('ALTER TABLE ' . $this::TABELA
                . ' DROP COLUMN idservidor, DROP COLUMN idcargo, DROP COLUMN idcentro, '
                . 'DROP COLUMN idlocacao, '
                //                    . 'CHANGE idpccs id_pccs INT(11) NULL COMMENT "PCCS" AFTER idlocacao, '
                . 'CHANGE id id INT(11) NOT NULL AUTO_INCREMENT COMMENT "ID do registro", '
                . 'CHANGE ano ano VARCHAR(4) NOT NULL COMMENT "Ano", '
                . 'CHANGE mes mes VARCHAR(2) NOT NULL COMMENT "Mês", '
                . 'CHANGE parcela parcela VARCHAR(3) NOT NULL COMMENT "parcela", '
                . 'CHANGE situacao situacao TINYINT(1) NULL COMMENT "Situação cadastral", '
                . 'CHANGE situacaofuncional situacaofuncional TINYINT(1) NULL COMMENT "Situação funcional" AFTER situacao, '
                . 'CHANGE id_cad_cargos id_cad_cargos INT(11) NULL COMMENT "Cargo", '
                . 'CHANGE id_cad_centros id_cad_centros INT(11) NULL COMMENT "Centro", '
                . 'CHANGE id_cad_departamentos id_cad_departamentos INT(11) NULL COMMENT "Locação", '
                //                    . 'CHANGE id_pccs id_pccs INT(11) NULL COMMENT "PCCS", '
                . 'CHANGE desconta_irrf desconta_irrf TINYINT(1) NULL COMMENT "Desconta IRRF", '
                . 'DROP COLUMN desconta_inss, '
                . 'DROP COLUMN desconta_rpps, '
                //                    . 'CHANGE desconta_inss desconta_inss TINYINT(1) NULL COMMENT "Desconta INSS", '
                //                    . 'CHANGE desconta_rpps desconta_rpps TINYINT(1) NULL COMMENT "Desconta RPPS", '
                . 'CHANGE desconta_sindicato desconta_sindicato TINYINT(1) NULL COMMENT "Desconta sindicato", '
                . 'CHANGE lanca_anuenio lanca_anuenio TINYINT(1) NULL COMMENT "Anuênio", '
                . 'CHANGE lanca_trienio lanca_trienio TINYINT(1) NULL COMMENT "Triênio", '
                . 'CHANGE lanca_quinquenio lanca_quinquenio TINYINT(1) NULL COMMENT "Quinquênio", '
                . 'CHANGE lanca_decenio lanca_decenio TINYINT(1) NULL COMMENT "Decênio", '
                . 'CHANGE lanca_salario lanca_salario TINYINT(1) NULL COMMENT "Lança salário", '
                . 'CHANGE lanca_funcao lanca_funcao TINYINT(1) NULL COMMENT "Lança função", '
                . 'CHANGE n_faltas n_faltas INT(3) NULL COMMENT "Dias de falta", '
                . 'CHANGE decimo_aniv decimo_aniv TINYINT(1) NULL COMMENT "13º no aniversário", '
                . 'CHANGE n_horaaula n_horaaula INT(3) NULL COMMENT "Horas aula" AFTER decimo_aniv, '
                . 'CHANGE n_adnoturno n_adnoturno INT(3) NULL COMMENT "Adicional noturno" AFTER n_horaaula, '
                . 'CHANGE n_hextra n_hextra INT(3) NULL COMMENT "Horas ewxtras" AFTER n_adnoturno, '
                . 'CHANGE categoria_receita categoria_receita CHAR(4) NULL COMMENT "Categoria receita" AFTER n_hextra, '
                . 'CHANGE previdencia previdencia TINYINT(1) NULL COMMENT "??? previdência" AFTER categoria_receita, '
                . 'CHANGE tipobeneficio tipobeneficio TINYINT(1) NULL COMMENT "??? tipo benif", '
                . 'CHANGE d_beneficio d_beneficio VARCHAR(10) NULL COMMENT "??? d_beneficio", '
                . 'CHANGE retorno_ocorrencia retorno_ocorrencia VARCHAR(2) NULL COMMENT "??? retorno_ocorr", '
                . 'CHANGE retorno_data retorno_data VARCHAR(10) NULL COMMENT "??? retorno_data", '
                . 'CHANGE retorno_valor retorno_valor FLOAT(11,2) NULL COMMENT "??? retorno_valor", '
                . 'CHANGE retorno_documento retorno_documento VARCHAR(50) NULL COMMENT "??? retorno_documento"');

            echo "*** Recriar índices finais***";
            $this->execute('ALTER TABLE ' . $this::TABELA . ' ADD CONSTRAINT `fk-' . $this::TABELA
                . '-cargos-cad_cargos-id` FOREIGN KEY (`id_cad_cargos`) '
                . 'REFERENCES `cad_cargos`(`id`) ON UPDATE CASCADE ON DELETE NO ACTION, '
                . 'ADD CONSTRAINT `fk-' . $this::TABELA . '-centros-cad_centros-id` '
                . 'FOREIGN KEY (`id_cad_centros`) '
                . 'REFERENCES `cad_centros`(`id`) ON UPDATE CASCADE ON DELETE NO ACTION, '
                . 'ADD CONSTRAINT `fk-' . $this::TABELA . '-departamentos-cad_departamentos-id` '
                . 'FOREIGN KEY (`id_cad_departamentos`) REFERENCES `cad_departamentos`(`id`) '
                . 'ON UPDATE CASCADE ON DELETE NO ACTION'); //, ADD CONSTRAINT `fk-'
            //                    . $this::TABELA . '-pccs-cad_pccs-id` FOREIGN KEY (`id_pccs`) '
            //                    . 'REFERENCES `cad_pccs`(`id`) ON UPDATE CASCADE ON DELETE NO ACTION');
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
        $salto = 100;
        $q = Yii::$app->db->createCommand(
            'select count(id) as quant from ' . $this::TABELA
        )
            ->queryOne();
        $quant = $q['quant'];
        $x = 0;
        // Primeira edição em massa dos dados
        do {
            $this->execute('UPDATE ' . $this::TABELA . ' f '
                . 'JOIN cad_servidores AS cs ON CAST(cs.matricula AS INTEGER) = CAST(f.idservidor AS INTEGER) '
                . 'SET f.created_at = UNIX_TIMESTAMP(NOW()), ' //f.slug = SHA(f.id), 
                . 'f.updated_at = UNIX_TIMESTAMP(NOW()), '
                . 'f.id_cad_servidores = cs.id, '
                . 'situacao = IF(UPPER(situacao) = UPPER("ADMITIDO"), 1, IF(UPPER(situacao) = UPPER("DEMITIDO"), 2, IF(UPPER(situacao) = UPPER("AFASTADO"), 3, 0))), '
                . 'desconta_irrf = IF(desconta_irrf = "S", 1, IF(desconta_irrf = "N", 0, null)), '
                . 'tp_previdencia = IF(desconta_inss = "S", 0,IF(desconta_rpps = "S", 4,NULL)), '
                //                    . 'desconta_inss = IF(desconta_inss = "S", 1, IF(desconta_inss = "N", 0, null)), '
                //                    . 'desconta_rpps = IF(desconta_rpps = "S", 1, IF(desconta_rpps = "N", 0, null)), '
                . 'lanca_anuenio = IF(lanca_anuenio = "S", 1, IF(lanca_anuenio = "N", 0, null)), '
                . 'lanca_trienio = IF(lanca_trienio = "S", 1, IF(lanca_trienio = "N", 0, null)), '
                . 'lanca_quinquenio = IF(lanca_quinquenio = "S", 1, IF(lanca_quinquenio = "N", 0, null)), '
                . 'lanca_decenio = IF(lanca_decenio = "S", 1, IF(lanca_decenio = "N", 0, null)), '
                . 'lanca_salario = IF(lanca_salario = "S", 1, IF(lanca_salario = "N", 0, null)), '
                . 'lanca_funcao = IF(lanca_funcao = "S", 1, IF(lanca_funcao = "N", 0, null)), '
                . 'decimo_aniv = IF(decimo_aniv = "S", 1, IF(decimo_aniv = "N", 0, null)), '
                . 'situacaofuncional = IF(situacaofuncional = "ATIVO", 1, IF(situacaofuncional = "APOSENTADO", 2, IF(situacaofuncional = "PENSIONISTA", 3, null))), '
                . 'desconta_sindicato = IF(lanca_funcao = "S", 1, IF(lanca_funcao = "N", 0, null)) '
                . 'WHERE f.id between ' . ($x + 1) . ' and ' . ($x + $salto));
            $x = $x + $salto;
            $quant = $quant - $salto;
            echo $quant;
        } while ($quant > 0);

        echo "*** Declarar enios ***";
        $this->execute(
            'UPDATE ' . $this::TABELA . ' f '
                . 'SET enio = IF(lanca_anuenio, 1, IF(lanca_trienio, 3, IF(lanca_quinquenio, 5, IF(lanca_decenio, 10, 0))));'
        );

        echo "*** Declarar cargos ***";
        $this->execute(
            'UPDATE ' . $this::TABELA . ' f '
                . 'JOIN cad_cargos cc ON cast(f.idcargo as unsigned) = cast(cc.id_cargo as unsigned) '
                . 'SET f.id_cad_cargos = cc.id '
        );

        echo "*** Declarar centros ***";
        $this->execute(
            'UPDATE ' . $this::TABELA . ' f '
                . 'JOIN cad_centros cc ON cast(f.idcentro as unsigned) = cast(cc.cod_centro as unsigned)'
                . 'SET f.id_cad_centros = cc.id '
        );

        echo "*** Declarar locações ***";
        $this->execute(
            'UPDATE ' . $this::TABELA . ' f '
                . 'JOIN cad_departamentos cc ON CAST(f.idlocacao as unsigned) = CAST(cc.id_departamento as unsigned)'
                . 'SET f.id_cad_departamentos = cc.id '
        );

        //        echo "*** Declarar pccs ***";
        //        $this->execute('UPDATE ' . $this::TABELA . ' f '
        //                . 'JOIN cad_pccs cc ON CAST(f.idpccs as unsigned) = CAST(cc.id_pccs as unsigned)'
        //                . 'SET f.id_pccs = cc.id '
        //        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180911_122036_fin_sfuncional cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m180911_122036_fin_sfuncional cannot be reverted.\n";

      return false;
      }
     */
}
