<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_basefixarefer".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_fin_base_fixa Codigo base fixa
 * @property string $id_fixa Codigo referencia
 * @property string $descricao Descrição
 * @property string $data Data
 * @property string $valor Valor base
 *
 * @property FinBasefixa $finBaseFixa
 */
class FinBasefixarefer extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * Setar o DB baseado na URL
     * @return type
     */
    public static function getDb() {
        $db = isset(Yii::$app->user->identity->cliente) ? strtolower(Yii::$app->user->identity->cliente) : 'db';
        return Yii::$app->$db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'fin_basefixarefer';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_fin_base_fixa', 'id_fixa', 'descricao', 'data'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_fin_base_fixa'], 'integer'],
            [['valor'], 'number'],
            [['slug', 'dominio', 'descricao'], 'string', 'max' => 255],
            [['id_fixa'], 'string', 'max' => 4],
            [['data'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['dominio', 'id_fin_base_fixa', 'id_fixa'], 'unique', 'targetAttribute' => ['dominio', 'id_fin_base_fixa', 'id_fixa']],
            [['id_fin_base_fixa'], 'exist', 'skipOnError' => true, 'targetClass' => FinBasefixa::className(), 'targetAttribute' => ['id_fin_base_fixa' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Domínio do cliente'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'id_fin_base_fixa' => Yii::t('yii', 'Codigo base fixa'),
            'id_fixa' => Yii::t('yii', 'Codigo referencia'),
            'descricao' => Yii::t('yii', 'Descrição'),
            'data' => Yii::t('yii', 'Data'),
            'valor' => Yii::t('yii', 'Valor base'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinBaseFixa() {
        return $this->hasOne(FinBasefixa::className(), ['id' => 'id_fin_base_fixa']);
    }

    /**
     * Operações executadas antes de salvar
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        $retorno = true;
        if (\pagamentos\controllers\FinParametrosController::getS()) {
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
        } else {
            $retorno = false;
            Yii::$app->session->setFlash('error', Yii::t('yii', Yii::$app->params['mmf2']));
            $this->addError('id', Yii::t('yii', Yii::$app->params['mmf2']));
        }

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
