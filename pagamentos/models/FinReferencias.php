<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_referencias".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_pccs PCC
 * @property int $id_classe Classe
 * @property int $referencia Referência
 * @property double $valor Valor 
 * @property string $data Data
 *
 * @property CadClasses $cadClasses
 * @property CadPccs $cadPccs
 */
class FinReferencias extends \yii\db\ActiveRecord {

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

    // Variáveis de cad_classes por causa de joins com fin_referencias
    public $i_ano_inicial, $i_ano_final;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'fin_referencias';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_pccs',
            'id_classe', 'referencia', 'valor', 'data'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_pccs',
            'id_classe',], 'integer'],
            [['valor'], 'number'],
            [['referencia'], 'number', 'min' => 1],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['dominio', 'id_pccs', 'id_classe', 'referencia'], 'unique', 'targetAttribute' => ['dominio', 'id_pccs', 'id_classe', 'referencia']],
            [['id_classe'], 'exist', 'skipOnError' => true, 'targetClass' => CadClasses::className(), 'targetAttribute' => ['id_classe' => 'id_classe']],
            [['id_pccs'], 'exist', 'skipOnError' => true, 'targetClass' => CadPccs::className(), 'targetAttribute' => ['id_pccs' => 'id_pccs']],
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
            'id_pccs' => Yii::t('yii', 'PCC'),
            'id_classe' => Yii::t('yii', 'Classe'),
            'referencia' => Yii::t('yii', 'Referência'),
            'valor' => Yii::t('yii', 'Valor'),
            'data' => Yii::t('yii', 'Data'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadClasses() {
        return $this->hasOne(CadClasses::className(), ['id_classe' => 'id_classe']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadPccs() {
        return $this->hasOne(CadPccs::className(), ['id_pccs' => 'id_pccs']);
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
//        $this->id_classe = str_pad($this->id_classe, 4, '0', STR_PAD_LEFT);
//        $this->id_pccs = str_pad($this->id_pccs, 4, '0', STR_PAD_LEFT);
        $this->referencia = str_pad($this->referencia, 4, '0', STR_PAD_LEFT);
        $this->valor = number_format($this->valor, 2, '.', '');
    }

}
