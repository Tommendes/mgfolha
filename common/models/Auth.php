<?php

namespace common\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property string $user
 * @property string $source
 * @property string $source_id
 * @property string $cover
 * @property string $first_name
 * @property string $last_name
 * @property string $age_range
 * @property string $link
 * @property string $gender
 * @property string $locale
 * @property string $picture
 * @property string $timezone
 * @property string $updated_time
 * @property string $verified
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user0
 */
class Auth extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'auth';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                    self::STATUS_INATIVO,
                    self::STATUS_ATIVO,
                    self::STATUS_CANCELADO
                ]
            ],
            [['user_id', 'source', 'source_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'user', 'source', 'source_id', 'cover', 'first_name', 'last_name', 'age_range', 'link', 'gender', 'locale', 'picture', 'timezone', 'updated_time', 'verified'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'user_id' => Yii::t('yii', 'User ID'),
            'email' => Yii::t('yii', 'Email'),
            'user' => Yii::t('yii', 'User'),
            'source' => Yii::t('yii', 'Source'),
            'source_id' => Yii::t('yii', 'Source ID'),
            'cover' => Yii::t('yii', 'Cover'),
            'first_name' => Yii::t('yii', 'First Name'),
            'last_name' => Yii::t('yii', 'Last Name'),
            'age_range' => Yii::t('yii', 'Age Range'),
            'link' => Yii::t('yii', 'Link'),
            'gender' => Yii::t('yii', 'Gender'),
            'locale' => Yii::t('yii', 'Locale'),
            'picture' => Yii::t('yii', 'Picture'),
            'timezone' => Yii::t('yii', 'Timezone'),
            'updated_time' => Yii::t('yii', 'Updated Time'),
            'verified' => Yii::t('yii', 'Verified'),
            'status' => Yii::t('yii', 'Status'),
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
        ];
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            $this->created_at = time();
        }
        $this->updated_at = time();
        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
