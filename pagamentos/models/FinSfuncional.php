<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;

/**
 * This is the model class for table "fin_sfuncional".
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property int $id_cad_servidores Servidor
 * @property string $ano Ano
 * @property string $mes Mês 
 * @property string $parcela Parcela
 * @property int $situacao Situação cadastral
 * @property int $situacaofuncional Tipo funcional
 * @property int $id_cad_cargos Cargo
 * @property int $id_cad_centros Centro
 * @property int $id_cad_departamentos Departamento
 * @property int $id_pccs PCC 
 * @property int $desconta_irrf Desconta IRRF
 * @property int $tp_previdencia Tipo da previdência
 * @property int $desconta_inss Desconta INSS
 * @property int $desconta_rpps Desconta RPPS
 * @property int $desconta_sindicato Desconta sindicato
 * @property int $enio Ênio
 * @property int $lanca_anuenio Anuênio
 * @property int $lanca_trienio Triênio
 * @property int $lanca_quinquenio Quinquênio
 * @property int $lanca_decenio Decênio 
 * @property int $lanca_salario Lança salário
 * @property int $lanca_funcao Lança função
 * @property int $n_faltas Dias de falta
 * @property int $decimo_aniv 13º no aniversário
 * @property int $n_horaaula Horas aula
 * @property int $n_adnoturno Adicional noturno
 * @property int $n_hextra Horas extras
 * @property int $ponto Horas trabalhadas
 * @property string $categoria_receita Categoria receita
 * @property int $previdencia ??? previdência
 * @property int $tipobeneficio ??? tipo benif
 * @property string $d_beneficio ??? d_beneficio
 * @property string $retorno_ocorrencia ??? retorno_ocorr
 * @property string $retorno_data ??? retorno_data
 * @property double $retorno_valor ??? retorno_valor
 * @property string $retorno_documento ??? retorno_documento
 *
 * @property CadServidores $cadServidores
 */
class FinSfuncional extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;
    const SITUACAO_ATIVO = 1;
    const SITUACAO_DEMITIDO = 2;
    const ENIO_1 = 'Anuênio';
    const ENIO_3 = 'Triênio';
    const ENIO_5 = 'Quinquênio';
    const ENIO_10 = 'Decênio';

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
        return 'fin_sfuncional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        $max_ponto = CadSfuncional::find()
                ->select('carga_horaria')
                ->where([
                    'dominio' => Yii::$app->user->identity->dominio,
//                            'ano' => Yii::$app->user->identity->per_ano,
//                            'mes' => Yii::$app->user->identity->per_mes,
//                            'parcela' => Yii::$app->user->identity->per_parcela,
                    'id_cad_servidores' => $this->id_cad_servidores,
                ])
                ->andWhere("CAST(cad_sfuncional.ano as unsigned) <= CAST('" . Yii::$app->user->identity->per_ano . "' as unsigned) 
                    AND CAST(cad_sfuncional.mes as unsigned) <= CAST('" . Yii::$app->user->identity->per_mes . "' as unsigned) 
                        AND CAST(cad_sfuncional.parcela as unsigned) <= CAST('" . Yii::$app->user->identity->per_parcela . "' as unsigned)")
                ->one();
        $max_ponto = (!$max_ponto == null) ? $max_ponto->carga_horaria * 4 : 0;
        return [
            ['status', 'default', 'value' => self::STATUS_ATIVO],
            ['status', 'in', 'range' => [
                    self::STATUS_INATIVO,
                    self::STATUS_ATIVO,
                    self::STATUS_CANCELADO,
                ]
            ],
            [['situacao'], 'required'],
            [['slug', 'dominio', 'created_at', 'updated_at', 'id_cad_servidores',
            'ano', 'mes', 'parcela',
            'situacaofuncional', 'id_cad_cargos', 'id_cad_centros',
            'id_cad_departamentos', 'id_pccs', 'tp_previdencia',// 'desconta_irrf', 'desconta_inss',
            'desconta_rpps', 'desconta_sindicato', 'lanca_anuenio',
            'lanca_trienio', 'lanca_quinquenio', 'lanca_decenio', 'lanca_salario',
            'lanca_funcao', 'n_faltas', 'previdencia', /* 'tipobeneficio', */
            'categoria_receita', 'ponto'], 'required', 'when' => function ($model) {
                    return $model->situacao >= 1;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#finsfuncional-situacao').val() >= 1;
                }"
            ],
            [['tipobeneficio', 'd_beneficio', 'previdencia', 'tipo'], 'required', 'when' => function ($model) {
                    return $model->situacaofuncional >= 2;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#finsfuncional-situacaofuncional').val() >= 2;
                }"
            ],
            [['status', 'created_at', 'updated_at', 'id_cad_servidores', 'situacao',
            'situacaofuncional', 'id_cad_cargos', 'id_cad_centros',
            'id_cad_departamentos', 'id_pccs', 'tp_previdencia',// 'desconta_irrf', 'desconta_inss',
            'desconta_rpps', 'desconta_sindicato', 'lanca_anuenio',
            'lanca_trienio', 'lanca_quinquenio', 'lanca_decenio', 'lanca_salario',
            'lanca_funcao', 'decimo_aniv',
            'previdencia', 'tipobeneficio', 'enio', 'ponto'], 'integer'],
            [['retorno_valor'], 'number'],
            [['n_horaaula', 'n_adnoturno', 'n_hextra'], 'number', 'max' => 99],
            [['ponto'], 'number', 'min' => 0, 'max' => $max_ponto],
            [['n_faltas'], 'number', 'min' => 0, 'max' => 31],
            [['slug', 'dominio'], 'string', 'max' => 255],
            [['ano', 'categoria_receita'], 'string', 'max' => 4],
            [['mes', 'retorno_ocorrencia'], 'string', 'max' => 2],
            [['parcela'], 'string', 'max' => 3],
            [['d_beneficio', 'retorno_data'], 'string', 'max' => 10],
            [['retorno_documento'], 'string', 'max' => 50],
            [['slug'], 'unique'],
            [['dominio', 'id_cad_servidores', 'ano', 'mes', 'parcela'], 'unique', 'targetAttribute' => ['dominio', 'id_cad_servidores', 'ano', 'mes', 'parcela']],
            [['id_cad_servidores'], 'exist', 'skipOnError' => true, 'targetClass' => CadServidores::className(), 'targetAttribute' => ['id_cad_servidores' => 'id']],
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
            'created_at' => Yii::t('yii', 'Registro em'),
            'updated_at' => Yii::t('yii', 'Atualização em'),
            'id_cad_servidores' => Yii::t('yii', 'Servidor'),
            'ano' => Yii::t('yii', 'Ano'),
            'mes' => Yii::t('yii', 'Mês'),
            'parcela' => Yii::t('yii', 'Parcela'),
            'situacao' => Yii::t('yii', 'Situação cadastral'),
            'situacaofuncional' => Yii::t('yii', 'Tipo funcional'),
            'id_cad_cargos' => Yii::t('yii', 'Cargo'),
            'id_cad_centros' => Yii::t('yii', 'Centro'),
            'id_cad_departamentos' => Yii::t('yii', 'Departamento'),
            'id_pccs' => Yii::t('yii', 'PCC'),
            'desconta_irrf' => Yii::t('yii', 'Desconta IRRF'),
            'tp_previdencia' => Yii::t('yii', 'Tipo da previdência'),
//            'desconta_inss' => Yii::t('yii', 'Desconta INSS'),
//            'desconta_rpps' => Yii::t('yii', 'Desconta RPPS'),
            'desconta_sindicato' => Yii::t('yii', 'Desconta sindicato'),
            'enio' => Yii::t('yii', 'Ênio(s)'),
            'lanca_anuenio' => Yii::t('yii', 'Anuênio'),
            'lanca_trienio' => Yii::t('yii', 'Triênio'),
            'lanca_quinquenio' => Yii::t('yii', 'Quinquênio'),
            'lanca_decenio' => Yii::t('yii', 'Decênio'),
            'lanca_salario' => Yii::t('yii', 'Lança salário'),
            'lanca_funcao' => Yii::t('yii', 'Lança função'),
            'n_faltas' => Yii::t('yii', 'Dias de falta'),
            'decimo_aniv' => Yii::t('yii', '13º no aniversário'),
            'n_horaaula' => Yii::t('yii', 'Horas aula'),
            'n_adnoturno' => Yii::t('yii', 'Adicional noturno'),
            'n_hextra' => Yii::t('yii', 'Horas extras'),
            'categoria_receita' => Yii::t('yii', 'Categoria receita'),
            'previdencia' => Yii::t('yii', 'Previdência'),
            'tipobeneficio' => Yii::t('yii', 'Tipo do benifício'),
            'd_beneficio' => Yii::t('yii', 'Data do benefício'),
            'retorno_ocorrencia' => Yii::t('yii', 'Ocorrência'),
            'retorno_data' => Yii::t('yii', 'Data'),
            'retorno_valor' => Yii::t('yii', 'Valor'),
            'retorno_documento' => Yii::t('yii', 'Documento'),
            'ponto' => Yii::t('yii', 'Horas trabalhadas'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCadServidores() {
        return $this->hasOne(CadServidores::className(), ['id' => 'id_cad_servidores']);
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
                $this->slug = is_null($this->slug) ? strtolower(sha1($this->tableName() . time())) : ($this->slug);
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
        $this->ponto = str_pad($this->ponto, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna o model CadServidor
     * @return type
     */
    public function getCadServidor($id_cad_servidores = null) {
        return CadServidores::findOne(is_null($id_cad_servidores) ? $this->id_cad_servidores : $id_cad_servidores);
    }

}
