<?php

use yii\db\Migration;

/**
 * Class m181126_163006_cad_format
 */
class m181126_163006_cad_format extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('UPDATE cad_bconvenios join cad_bancos on cad_bancos.id = cad_bconvenios.id_cad_bancos set cad_bconvenios.id_cad_bancos = cad_bancos.febraban');
        $this->execute('UPDATE cad_scertidao join cad_servidores on cad_servidores.id = cad_scertidao.id_cad_servidores set cad_scertidao.tipo = 0 where cad_servidores.estado_civil = 2');
        $this->execute('UPDATE cad_scertidao set cad_scertidao.tipo = 1 where cad_scertidao.tipo is null');
        $this->execute('TRUNCATE TABLE cad_srecadastro');
        $this->execute('UPDATE cad_sfuncional SET ocorrencia = NULL WHERE !(CAST(ocorrencia as unsigned) > 0)');
        $this->execute('UPDATE cad_servidores SET email = LOWER(email)');
        $this->execute('UPDATE cad_sfuncional SET id_cad_principal = (SELECT id FROM cad_servidores WHERE matricula = id_cad_principal AND dominio = cad_sfuncional.dominio)');
        $this->execute('UPDATE cad_sfuncional SET id_cad_principal = id WHERE id_cad_principal IS NULL OR id_cad_principal = "" OR LENGTH(TRIM(id_cad_principal)) = 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181126_163006_cad_format cannot be reverted.\n";

        return false;
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      } 

      public function down()
      {
      echo "m181126_163006_cad_format cannot be reverted.\n";

      return false;
      }
     */
}
