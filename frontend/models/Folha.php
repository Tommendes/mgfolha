<?php

namespace frontend\models;

use Yii;
use common\controllers\SisParamsController;
use yii\base\Model;

/**
 * Modelo de classe para geração|edição da "folha" de pagamento
 */
class Folha extends Model {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;

    public $dominio;
    public $status;
    public $ano;
    public $mes;
    public $parcela;

    public static function tableName() {
        return null;
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
            [['dominio', 'ano', 'mes', 'parcela', 'status',], 'required'],
            [['dominio',], 'string', 'max' => 255],
            [['ano',], 'string', 'max' => 4],
            [['mes',], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'dominio' => Yii::t('yii', 'Dominio'),
            'status' => Yii::t('yii', 'Status'),
            'ano' => Yii::t('yii', 'Ano'),
            'mes' => Yii::t('yii', 'Mês'),
            'parcela' => Yii::t('yii', 'Parcela'),
        ];
    }

}
