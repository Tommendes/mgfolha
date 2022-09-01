<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_options".
 *
 * @property int $id ID do registro
 * @property int $id_user ID do usuário
 * @property string $geo_lt Geo Latitude atual
 * @property string $geo_ln Geo Longitude atual
 * @property int $evento Evento do registro
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 *
 * @property User $user
 */
class UserOptions extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc} 
     */
    public static function tableName() {
        return 'user_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id_user', 'created_at', 'updated_at'], 'required'],
            [['id_user', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['geo_lt', 'geo_ln'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'id_user' => Yii::t('yii', 'ID do usuário'),
            'geo_lt' => Yii::t('yii', 'Geo Latitude atual'),
            'geo_ln' => Yii::t('yii', 'Geo Longitude atual'),
            'evento' => Yii::t('yii', 'Evento do registro'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

}
