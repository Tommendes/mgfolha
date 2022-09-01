<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_cidades".
 *
 * @property int $id
 * @property string $slug Id de chamada do registro
 * @property string $uf_nome Nome do estado
 * @property string $uf_id ID do estado
 * @property string $uf_abrev Abreviação do nome do estado
 * @property string $municipio_id ID do municipio
 * @property string $municipio_nome Nome do municipio
 * @property string $data_registro
 * @property int $created_at Data de registro
 * @property int $updated_at Última da atualização
 */
class CadCidades extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'cad_cidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                    self::STATUS_INATIVO,
                    self::STATUS_ATIVO,
                    self::STATUS_CANCELADO,
                ]
            ],
            [['slug', 'created_at', 'updated_at'], 'required'],
            [['data_registro'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['slug', 'uf_nome', 'uf_id', 'uf_abrev', 'municipio_id', 'municipio_nome'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'uf_nome' => 'Uf Nome',
            'uf_id' => 'Uf ID',
            'uf_abrev' => 'Uf Abrev',
            'municipio_id' => 'Municipio ID',
            'municipio_nome' => 'Municipio Nome',
            'data_registro' => 'Data Registro',
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
        ];
    }

    /**
     * Operações executadas antes de salvar
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
            $this->slug = strtolower(sha1($this->tableName() . time()));
            $this->dominio = Yii::$app->user->identity->dominio;
        } else {
            if (!isset(Yii::$app->user->identity->dominio)) {
                $this->dominio = Yii::$app->user->identity->dominio;
            }
        }
        $this->updated_at = time();

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
