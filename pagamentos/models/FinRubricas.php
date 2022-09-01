<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_rubricas".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int|null $id_cad_servidores Servidor
 * @property int|null $id_fin_eventos Rúbrica
 * @property string $ano Ano
 * @property string $mes Mês
 * @property string $parcela Parcela
 * @property string|null $referencia Referência
 * @property float $valor_baseespecial Valor base especial
 * @property float $valor_base Valor base
 * @property float $valor_basefixa Valor base fixa
 * @property float $valor_desconto Desconto
 * @property float $valor_percentual Percentual
 * @property float $valor_saldo Saldo
 * @property float $valor Provento|Desconto
 * @property float $valor_patronal Valor patronal
 * @property float $valor_maternidade Valor maternidade
 * @property int $prazo Parcela atual
 * @property int $prazot Parcela final
 *
 * @property CadServidores $cadServidores
 * @property FinEventos $finEventos
 */
class FinRubricas extends \yii\db\ActiveRecord {

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
        return 'fin_rubricas';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'ano', 'mes', 'parcela'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores', 'id_fin_eventos', 'prazo', 'prazot'], 'integer'],
            [['valor_baseespecial', 'valor_base', 'valor_basefixa', 'valor_desconto', 'valor_percentual', 'valor_saldo', 'valor', 'valor_patronal', 'valor_maternidade'], 'number'],
            [['slug', 'dominio', 'referencia'], 'string', 'max' => 255],
            [['ano'], 'string', 'max' => 4],
            [['mes'], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
            [['slug'], 'unique'],
            [['dominio', 'id_cad_servidores', 'id_fin_eventos', 'ano', 'mes', 'parcela'], 'unique', 'targetAttribute' => ['dominio', 'id_cad_servidores', 'id_fin_eventos', 'ano', 'mes', 'parcela']],
            [['id_cad_servidores'], 'exist', 'skipOnError' => true, 'targetClass' => CadServidores::className(), 'targetAttribute' => ['id_cad_servidores' => 'id']],
            [['id_fin_eventos'], 'exist', 'skipOnError' => true, 'targetClass' => FinEventos::className(), 'targetAttribute' => ['id_fin_eventos' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Dominio'),
            'evento' => Yii::t('yii', 'Evento'),
            'created_at' => Yii::t('yii', 'Created At'),
            'updated_at' => Yii::t('yii', 'Updated At'),
            'id_cad_servidores' => Yii::t('yii', 'Id Cad Servidores'),
            'id_fin_eventos' => Yii::t('yii', 'Rúbrica'),
            'ano' => Yii::t('yii', 'Ano'),
            'mes' => Yii::t('yii', 'Mes'),
            'parcela' => Yii::t('yii', 'Parcela'),
            'referencia' => Yii::t('yii', 'Referencia'),
            'valor_baseespecial' => Yii::t('yii', 'Valor Baseespecial'),
            'valor_base' => Yii::t('yii', 'Valor Base'),
            'valor_basefixa' => Yii::t('yii', 'Valor Basefixa'),
            'valor_desconto' => Yii::t('yii', 'Valor Desconto'),
            'valor_percentual' => Yii::t('yii', 'Valor Percentual'),
            'valor_saldo' => Yii::t('yii', 'Valor Saldo'),
            'valor' => Yii::t('yii', 'Valor'),
            'valor_patronal' => Yii::t('yii', 'Valor Patronal'),
            'valor_maternidade' => Yii::t('yii', 'Valor Maternidade'),
            'prazo' => Yii::t('yii', 'Prazo'),
            'prazot' => Yii::t('yii', 'Prazot'),
        ];
    }

    /**
     * Gets query for [[CadServidores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCadServidores() {
        return $this->hasOne(CadServidores::className(), ['id' => 'id_cad_servidores']);
    }

    /**
     * Gets query for [[FinEventos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinEventos() {
        return $this->hasOne(FinEventos::className(), ['id' => 'id_fin_eventos']);
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
                $this->slug = is_null($this->slug) ? strtolower(sha1($this->tableName() . time())) : $this->slug;
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
     * Retorna o model do evento relacionado
     * @return type
     */
    public function getFinEvento() {
        return FinEventos::find()
                        ->where(['id' => $this->id_fin_eventos])
                        ->one();
    }

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne(is_null($id_cad_servidores) ? $this->id_cad_servidores : $id_cad_servidores);
    }

}
