<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "cad_sferias".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $id_cad_servidores ID do servidor
 * @property int $status
 * @property string $dominio
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $d_ferias Data inicial ds férias
 * @property int $periodo Período
 * @property string $observacoes Observações
 *
 * @property CadServidores $cadServidores
 */
class CadSferias extends \yii\db\ActiveRecord {

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
        return 'cad_sferias';
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
            [['id_cad_servidores', 'created_at', 'updated_at', 'periodo', 'd_ferias'], 'required'],
            [['id_cad_servidores', 'status', 'evento', 'created_at', 'updated_at',], 'integer'],
            [['observacoes'], 'string'],
            [['periodo'], 'number', 'min' => 1, 'max' => 30],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['d_ferias'], 'string', 'max' => 10],
            [['id_cad_servidores', 'd_ferias', 'dominio'], 'unique', 'targetAttribute' => ['id_cad_servidores', 'd_ferias', 'dominio']],
            [['id_cad_servidores'], 'exist', 'skipOnError' => true, 'targetClass' => CadServidores::className(), 'targetAttribute' => ['id_cad_servidores' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'slug' => Yii::t('yii', 'Slug'),
            'id_cad_servidores' => Yii::t('yii', 'ID do servidor'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Dominio'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'd_ferias' => Yii::t('yii', 'Data inicial ds férias'),
            'periodo' => Yii::t('yii', 'Período de dias'),
            'observacoes' => Yii::t('yii', 'Observações'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadServidores() {
        return $this->hasOne(CadServidores::className(), ['id' => 'id_cad_servidores']);
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

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public static function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne(is_null($id_cad_servidores) ? $this->id_cad_servidores : $id_cad_servidores);
    }

}
