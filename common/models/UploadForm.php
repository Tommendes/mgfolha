<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
    public $imageFiles;
    public $base64;
    public $id_user;

    public function rules() {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'imageFiles' => Yii::t('yii', 'Arquivo'),
        ];
    }

    public function upload($root, $name) {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $file->saveAs(strtolower($root . (!is_null($name) ? $name : $file->baseName) . '.' . $file->extension));
            }
            return true;
        } else {
            return false;
        }
    }

}
