<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_faixas".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $tipo Tipo da faixa
 * @property string $data Data entra em vigor
 * @property string $faixa Faixa
 * @property string $v_final1 Valor final da faixa 1
 * @property string $v_faixa1 Valor da faixa 1
 * @property string $v_deduzir1 Deduzir da faixa 1
 * @property string $v_final2 Valor final da faixa 2
 * @property string $v_faixa2 Valor da faixa 2
 * @property string $v_deduzir2 Deduzir da faixa 2
 * @property string $v_final3 Valor final da faixa 3
 * @property string $v_faixa3 Valor da faixa 3
 * @property string $v_deduzir3 Deduzir da faixa 3
 * @property string $v_final4 Valor final da faixa 4
 * @property string $v_faixa4 Valor da faixa 4
 * @property string $v_deduzir4 Deduzir da faixa 4
 * @property string $v_final5 Valor final da faixa 5
 * @property string $v_faixa5 Valor da faixa 5
 * @property string $v_deduzir5 Deduzir da faixa 5
 * @property string $deduzir_dependente Deduzir por dependente
 * @property string $salario_vigente Alíquota
 * @property string $inss_teto INSS teto
 * @property string $inss_patronal INSS patronal
 * @property string $inss_rat INSS Rat
 * @property string $inss_fap INSS Fap
 * @property string $rpps_patronal rpps patronal
 */
class FinFaixas extends \yii\db\ActiveRecord {

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
        return 'fin_faixas';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'tipo', 'data', 'faixa'], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'tipo'], 'integer'],
            [['v_final1', 'v_faixa1', 'v_deduzir1', 'v_final2', 'v_faixa2', 'v_deduzir2', 'v_final3', 'v_faixa3', 'v_deduzir3', 'v_final4', 'v_faixa4', 'v_deduzir4', 'v_final5', 'v_faixa5', 'v_deduzir5', 'deduzir_dependente', 'salario_vigente', 'inss_teto', 'inss_patronal', 'inss_rat', 'inss_fap', 'rpps_patronal'], 'number'],
            [['slug', 'dominio', 'faixa'], 'string', 'max' => 255],
            [['data'], 'string', 'max' => 10],
            [['slug'], 'unique'],
            [['dominio', 'tipo', 'faixa', 'data'], 'unique', 'targetAttribute' => ['dominio', 'tipo', 'faixa', 'data']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('yii', 'ID do registro'),
            'slug' => Yii::t('yii', 'Slug'),
            'status' => Yii::t('yii', 'Status'),
            'dominio' => Yii::t('yii', 'Domínio do cliente'),
            'evento' => Yii::t('yii', 'Último evento'),
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'tipo' => Yii::t('yii', 'Tipo da faixa'),
            'data' => Yii::t('yii', 'Data entra em vigor'),
            'faixa' => Yii::t('yii', 'Faixa'),
            'v_final1' => Yii::t('yii', 'Valor final da faixa 1'),
            'v_faixa1' => Yii::t('yii', 'Valor da faixa 1'),
            'v_deduzir1' => Yii::t('yii', 'Deduzir da faixa 1'),
            'v_final2' => Yii::t('yii', 'Valor final da faixa 2'),
            'v_faixa2' => Yii::t('yii', 'Valor da faixa 2'),
            'v_deduzir2' => Yii::t('yii', 'Deduzir da faixa 2'),
            'v_final3' => Yii::t('yii', 'Valor final da faixa 3'),
            'v_faixa3' => Yii::t('yii', 'Valor da faixa 3'),
            'v_deduzir3' => Yii::t('yii', 'Deduzir da faixa 3'),
            'v_final4' => Yii::t('yii', 'Valor final da faixa 4'),
            'v_faixa4' => Yii::t('yii', 'Valor da faixa 4'),
            'v_deduzir4' => Yii::t('yii', 'Deduzir da faixa 4'),
            'v_final5' => Yii::t('yii', 'Valor final da faixa 5'),
            'v_faixa5' => Yii::t('yii', 'Valor da faixa 5'),
            'v_deduzir5' => Yii::t('yii', 'Deduzir da faixa 5'),
            'deduzir_dependente' => Yii::t('yii', 'Deduzir por dependente'),
            'salario_vigente' => Yii::t('yii', 'Alíquota'),
            'inss_teto' => Yii::t('yii', 'INSS teto'),
            'inss_patronal' => Yii::t('yii', 'INSS patronal'),
            'inss_rat' => Yii::t('yii', 'INSS Rat'),
            'inss_fap' => Yii::t('yii', 'INSS Fap'),
            'rpps_patronal' => Yii::t('yii', 'RPPS patronal'),
        ];
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

}
