<?php

namespace pagamentos\models;

use Yii;
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
    public $titulo;
    public $descricao;
    public $id_cad_servidores;
    public $id_cad_centros;
    public $id_cad_departamentos;
    public $id_cad_cargos;
    public $id_cad_locais_trabalho;
    public $id_negado;
    public $SUBREPORT_DIR;
    public $quebra;
    public $isNewRecord;

    public function __construct($new = true) {
        return $this->isNewRecord = $new;
    }

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
            [['dominio', 'status', 'ano', 'mes', 'parcela',], 'required'],
            [['dominio', 'titulo', 'descricao', 'id_cad_servidores',
            'id_negado', 'SUBREPORT_DIR',], 'string', 'max' => 255],
            [['ano',], 'string', 'max' => 4],
            [['mes',], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
            [['quebra', 'id_cad_centros', 'id_cad_locais_trabalho', 'id_cad_cargos', 'id_cad_departamentos',], 'integer'],
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
            'titulo' => Yii::t('yii', 'Título'),
            'descricao' => Yii::t('yii', 'Descrição'),
            'id_cad_servidores' => Yii::t('yii', 'Servidores'),
            'id_cad_centros' => Yii::t('yii', 'Centros de custo'),
            'id_cad_locais_trabalho' => Yii::t('yii', 'Locais de trabalho'),
            'id_cad_departamentos' => Yii::t('yii', 'Departamentos'),
            'id_cad_cargos' => Yii::t('yii', 'Cargos'),
            'quebra' => Yii::t('yii', 'Quebra de página'),
            'SUBREPORT_DIR' => Yii::t('yii', 'SUBREPORT_DIR'),
        ];
    }

}
