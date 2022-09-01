<?php

namespace common\models;

use Yii;
use common\models\User;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property string $slug Id de chamada do registro
 * @property int $status Status
 * @property int $id_registro ID do registro na tabela
 * @property string $dominio Domínio do cliente
 * @property string $evento Evento
 * @property string $classevento Classe
 * @property string $tabela_bd Tabela
 * @property string $ip IP
 * @property string $geo_lt Geo Latitude
 * @property string $geo_ln Geo Longitude
 * @property string $id_user
 * @property int $created_at Data de registro
 * @property int $updated_at Última da atualização
 *
 * @property Empresa[] $empresas
 */
class SisEvents extends \yii\db\ActiveRecord {

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
        return 'sis_events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['slug', 'id_user', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['evento'], 'string'],
            [['slug'], 'string', 'max' => 255],
            [['dominio'], 'string', 'max' => 30],
            [['classevento', 'tabela_bd', 'geo_lt', 'geo_ln'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 15],
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
            'dominio' => Yii::t('yii', 'Domínio do cliente'),
            'evento' => Yii::t('yii', 'Evento'),
            'classevento' => Yii::t('yii', 'Classe'),
            'tabela_bd' => Yii::t('yii', 'Tabela'),
            'ip' => Yii::t('yii', 'IP'),
            'geo_lt' => Yii::t('yii', 'Geo Latitude'),
            'geo_ln' => Yii::t('yii', 'Geo Longitude'),
            'id_user' => Yii::t('yii', 'Usuário'),
            'created_at' => Yii::t('yii', 'Data de registro'),
            'updated_at' => Yii::t('yii', 'Última da atualização'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresas() {
        return $this->hasMany(Empresa::className(), ['evento' => 'id']);
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
//            $this->slug = strtolower(sha1($this->tableName() . time()));
            $this->dominio = isset(Yii::$app->user->identity->dominio) ? Yii::$app->user->identity->dominio : 'Visitante';
        } else {
            
        }
        $this->updated_at = time();
        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

    public function getUsername() {
        if (isset($this->id_user) && !is_null($this->id_user)):
            return User::findOne($this->id_user)->username;
        else:
            return null;
        endif;
    }

}
