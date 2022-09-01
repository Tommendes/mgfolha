<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_pccs".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $id_pccs ID PCCS
 * @property string $nome_pccs Nome
 * @property string $nivel_pccs Nível
 *
 * @property CadClasses[] $cadClasses
 * @property FinReferencias[] $finReferencias
 */
class CadPccs extends \yii\db\ActiveRecord {

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
        return 'cad_pccs';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_pccs', 'nome_pccs'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'nome_pccs', 'nivel_pccs'], 'string', 'max' => 255],
            [['id_pccs'], 'string', 'max' => 4],
            [['slug'], 'unique'],
            [['dominio', 'id_pccs'], 'unique', 'targetAttribute' => ['dominio', 'id_pccs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'slug' => 'Slug',
            'status' => 'Status',
            'dominio' => 'Dominio',
            'evento' => 'Evento',
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
            'id_pccs' => 'PCC',
            'nome_pccs' => 'Nome PCC',
            'nivel_pccs' => 'Nivel PCC',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadClasses() {
        return $this->hasMany(CadClasses::className(), ['id_pccs' => 'id_pccs']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFinReferencias() {
        return $this->hasMany(FinReferencias::className(), ['id_pccs' => 'id_pccs']);
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
