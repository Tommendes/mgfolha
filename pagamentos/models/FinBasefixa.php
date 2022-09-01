<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_basefixa".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $id_base_fixa Codigo base fixa
 * @property string $nome_base_fixa Nome base fixa
 *
 * @property FinBasefixaeventos[] $finBasefixaeventos
 * @property FinBasefixarefer[] $finBasefixarefers
 */
class FinBasefixa extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    public /* Variáreis de referência com pagamentos/models/FinBasefixarefer */
            $fixa_refer_id,
            $fixa_refer_id_fixa,
            $fixa_refer_descricao,
            $fixa_refer_data,
            $fixa_refer_valor;

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
        return 'fin_basefixa';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_base_fixa', 'nome_base_fixa'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'nome_base_fixa'], 'string', 'max' => 255],
            [['id_base_fixa'], 'string', 'max' => 4],
            [['slug'], 'unique'],
            [['dominio', 'id_base_fixa'], 'unique', 'targetAttribute' => ['dominio', 'id_base_fixa']],
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
            'id_base_fixa' => Yii::t('yii', 'Codigo base fixa'),
            'nome_base_fixa' => Yii::t('yii', 'Nome base fixa'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinBasefixaeventos() {
        return $this->hasMany(FinBasefixaeventos::className(), ['id_fin_base_fixa' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinBasefixarefers() {
        return $this->hasMany(FinBasefixarefer::className(), ['id_fin_base_fixa' => 'id']);
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
