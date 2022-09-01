<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "params".
 *
 * @property int $id
 * @property string $slug Id de chamada do registro
 * @property int $status Status
 * @property string $dominio Domínio do cliente
 * @property string $grupo Grupo do parâmetro
 * @property string $parametro Parâmetro
 * @property string $label Label do parametro
 * @property string $data_registro Data do registro
 * @property int $evento ID do evento relacionado
 * @property int $created_at Data de registro
 * @property int $updated_at Última da atualização
 */
class SisParams extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'params';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['slug', 'created_at', 'updated_at'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['parametro', 'label'], 'string'],
            [['data_registro'], 'safe'],
            [['slug'], 'string', 'max' => 255],
            [['dominio'], 'string', 'max' => 40],
            [['grupo'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'slug' => Yii::t('yii', 'Id de chamada do registro'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Domínio do parâmetro'),
            'grupo' => Yii::t('yii', 'Grupo do parâmetro'),
            'parametro' => Yii::t('yii', 'Parâmetro'),
            'label' => Yii::t('yii', 'Label do parâmetro'),
            'data_registro' => Yii::t('yii', 'Data do registro'),
            'evento' => Yii::t('yii', 'ID do evento relacionado'),
            'created_at' => Yii::t('yii', 'Data de registro'),
            'updated_at' => Yii::t('yii', 'Última da atualização'),
        ];
    }

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

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

    /**
     * Retorna um label para o status informado
     * @param type $value
     * @return string
     */
    public static function getStatusLabel($value = null) {
        $result = '';
        switch ($value) {
            case self::STATUS_INATIVO: $result = 'Inativo';
                break;
            case self::STATUS_ATIVO: $result = 'Ativo';
                break;
            case self::STATUS_CANCELADO: $result = 'Cancelado';
                break;
        }
        return $result;
    }

}
