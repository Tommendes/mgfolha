<?php

namespace frontend\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "sis_shortener".
 *
 * @property int $id
 * @property string $url
 * @property string $shortened
 */
class SisShortener extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'sis_shortener';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['url', 'shortened'], 'string', 'max' => 255],
            [['shortened'], 'unique'],
            [['url'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'url' => Yii::t('yii', 'Url'),
            'shortened' => Yii::t('yii', 'Shortened'),
        ];
    }

    public function beforeSave($insert) {
        $retorno = true;
        if ($insert) {
            
        } else {
            
        }

        $id = rand(10000, 99999);
        $shorturl = base_convert($id, 20, 36);
        $this->shortened = $shorturl;
//        $this->shortened = SisShortenerController::short($this->url, false);
        return $retorno;
    }

    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
    }

}
