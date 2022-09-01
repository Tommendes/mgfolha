<?php

namespace pagamentos\models;

use Yii;
use common\controllers\SisParamsController;
use yiibr\brvalidator\CpfValidator;
use pagamentos\controllers\CadServidoresController;

/**
 * This is the model class for table "cad_servidores". 
 *
 * @property int $id ID do registro
 * @property string $slug
 * @property int $status
 * @property string $dominio Domínio do cliente
 * @property int $evento Último evento
 * @property int $created_at Registro em
 * @property int $updated_at Atualização em
 * @property string $url_foto Foto do servidor
 * @property int $matricula Matricula
 * @property string $nome Nome
 * @property string $cpf CPF
 * @property string $rg RG
 * @property string $rg_emissor RG emissor
 * @property string $rg_uf RG UF
 * @property string $rg_d RG emissão
 * @property string $pispasep PIS
 * @property string $pispasep_d PIS emissão
 * @property string $titulo Título
 * @property string $titulosecao Tit sessão
 * @property string $titulozona Tit zona
 * @property string $ctps CTPS
 * @property string $ctps_serie CTPS série
 * @property string $ctps_uf CTPS UF
 * @property string $ctps_d CTPS emissão
 * @property string $nascimento_d Nascimento
 * @property string $pai Pai
 * @property string $mae Mãe
 * @property string $cep Cep
 * @property string $logradouro Logradouro
 * @property string $numero Número
 * @property string $complemento Complemento
 * @property string $bairro Bairro
 * @property string $cidade Cidade
 * @property string $uf Estado
 * @property string $naturalidade Naturalidade
 * @property string $naturalidade_uf Nat UF
 * @property string $telefone Telefone
 * @property string $celular Celular
 * @property string $email Email
 * @property int $idbanco Banco
 * @property string $banco_agencia Agência
 * @property string $banco_agencia_digito Ag dígito
 * @property string $banco_conta Conta
 * @property string $banco_conta_digito Cta dígito
 * @property string $banco_operacao Cta Operação
 * @property string $nacionalidade Nacionalidade
 * @property int $sexo Sexo
 * @property int $raca Raça
 * @property int $estado_civil Estado civil
 * @property int $tipodeficiencia Tipo deficiência
 * @property string $d_admissao Admissão data
 *
 * @property CadScertidao[] $cadScertidaos
 * @property CadSdependentes[] $cadSdependentes
 * @property CadSferias[] $cadSferias
 * @property CadSfuncional $cadSfuncional
 * @property CadSrecadastro[] $cadSrecadastros
 */
class CadServidores extends \yii\db\ActiveRecord {

    const STATUS_INATIVO = 0;
    const STATUS_ATIVO = 10;
    const STATUS_CANCELADO = 99;
    const NACIONALIDADE_BR = 10;

    public $situacao;
    public $situacaoFuncional;

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
        return 'cad_servidores';
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
            [['slug', 'dominio', 'created_at', 'updated_at', 'matricula', 'nome', 'cpf',
            'rg', 'rg_uf', 'rg_d', 'rg_emissor', 'uf', 'naturalidade', 'naturalidade_uf',
            'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'uf',
//            'ctps_uf', 'ctps', 'ctps_serie', 'ctps_d', 
            'nascimento_d', 'mae', 'd_admissao',
            'sexo', 'raca', 'estado_civil', 'tipodeficiencia', 'nacionalidade',], 'required'],
            [['status', 'evento', 'created_at', 'updated_at', 'matricula',
            'idbanco', 'sexo', 'raca', 'estado_civil', 'tipodeficiencia', 'numero', 'situacao', 'situacaoFuncional'], 'integer'],
            [['slug', 'dominio', 'url_foto', 'nome', 'logradouro', 'bairro',
            'cidade', 'naturalidade', 'celular', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['cpf'], 'string', 'max' => 14],
            [['cpf'], CpfValidator::className()],
            [['rg', 'rg_emissor', 'titulo'], 'string', 'max' => 20],
            [['rg_uf', 'ctps_uf', 'uf', 'naturalidade_uf', 'nacionalidade'], 'string', 'max' => 2],
            [['rg_d', 'pispasep_d', 'ctps_d', 'nascimento_d', 'd_admissao'], 'string', 'max' => 10],
            [['pispasep', 'telefone'], 'string', 'max' => 14],
            [['titulosecao', 'titulozona'], 'string', 'max' => 4],
            [['ctps'], 'string', 'max' => 7],
            [['ctps_serie', 'banco_agencia', 'banco_operacao'], 'string', 'max' => 5],
            [['pai', 'mae'], 'string', 'max' => 100],
            [['cep'], 'string', 'max' => 10],
            [['complemento'], 'string', 'max' => 40],
            [['banco_agencia_digito', 'banco_conta_digito'], 'string', 'max' => 1],
            [['banco_conta'], 'string', 'max' => 12],
            [['slug'], 'unique'],
            [['dominio', 'matricula'], 'unique', 'targetAttribute' => ['dominio', 'matricula']],
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
            'url_foto' => Yii::t('yii', 'Foto do servidor'),
            'matricula' => Yii::t('yii', 'Matricula'),
            'nome' => Yii::t('yii', 'Nome'),
            'cpf' => Yii::t('yii', 'CPF'),
            'rg' => Yii::t('yii', 'RG'),
            'rg_emissor' => Yii::t('yii', 'RG emissor'),
            'rg_uf' => Yii::t('yii', 'RG UF'),
            'rg_d' => Yii::t('yii', 'RG emissão'),
            'pispasep' => Yii::t('yii', 'PIS'),
            'pispasep_d' => Yii::t('yii', 'PIS emissão'),
            'titulo' => Yii::t('yii', 'Título'),
            'titulosecao' => Yii::t('yii', 'Título sessão'),
            'titulozona' => Yii::t('yii', 'Título zona'),
            'ctps' => Yii::t('yii', 'CTPS'),
            'ctps_serie' => Yii::t('yii', 'CTPS série'),
            'ctps_uf' => Yii::t('yii', 'CTPS UF'),
            'ctps_d' => Yii::t('yii', 'CTPS emissão'),
            'nascimento_d' => Yii::t('yii', 'Nascimento'),
            'pai' => Yii::t('yii', 'Pai'),
            'mae' => Yii::t('yii', 'Mãe'),
            'cep' => Yii::t('yii', 'Cep'),
            'logradouro' => Yii::t('yii', 'Logradouro'),
            'numero' => Yii::t('yii', 'Número'),
            'complemento' => Yii::t('yii', 'Complemento'),
            'bairro' => Yii::t('yii', 'Bairro'),
            'cidade' => Yii::t('yii', 'Cidade'),
            'uf' => Yii::t('yii', 'Estado'),
            'naturalidade' => Yii::t('yii', 'Naturalidade'),
            'naturalidade_uf' => Yii::t('yii', 'Naturalidade UF'),
            'telefone' => Yii::t('yii', 'Telefone'),
            'celular' => Yii::t('yii', 'Celular'),
            'email' => Yii::t('yii', 'Email'),
            'idbanco' => Yii::t('yii', 'Banco'),
            'banco_agencia' => Yii::t('yii', 'Agência'),
            'banco_agencia_digito' => Yii::t('yii', 'Ag dígito'),
            'banco_conta' => Yii::t('yii', 'Conta'),
            'banco_conta_digito' => Yii::t('yii', 'Conta dígito'),
            'banco_operacao' => Yii::t('yii', 'Conta Operação'),
            'nacionalidade' => Yii::t('yii', 'Nacionalidade'),
            'sexo' => Yii::t('yii', 'Sexo'),
            'raca' => Yii::t('yii', 'Raça'),
            'estado_civil' => Yii::t('yii', 'Estado civil'),
            'tipodeficiencia' => Yii::t('yii', 'Tipo deficiência'),
            'd_admissao' => Yii::t('yii', 'Admissão'),
            'situacao' => Yii::t('yii', 'Situação cadastral'),
            'situacaoFuncional' => Yii::t('yii', 'Tipo funcional'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getCadScertidaos() {
        return $this->hasMany(CadScertidao::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getCadSdependentes() {
        return $this->hasMany(CadSdependentes::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getCadSferias() {
        return $this->hasMany(CadSferias::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getCadSfuncional() {
        return $this->hasOne(CadSfuncional::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getCadSrecadastros() {
        return $this->hasMany(CadSrecadastro::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getFinRubricas() {
        return $this->hasMany(FinRubricas::className(), ['id_cad_servidores' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery 
     */
    public function getFinSfuncionals() {
        return $this->hasMany(FinSfuncional::className(), ['id_cad_servidores' => 'id']);
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
                $this->matricula = CadServidoresController::getMatricula();
            } else {
                if (!isset(Yii::$app->user->identity->dominio)) {
                    $this->dominio = Yii::$app->user->identity->dominio;
                }
            }
            $this->cep = str_replace('.', '', str_replace('-', '', $this->cep));
            $this->cpf = str_replace('.', '', str_replace('-', '', $this->cpf));
            $this->updated_at = time();
            if (empty($this->url_foto)) {
                $this->url_foto = time();
            }

            foreach ($this as $key => $value) {
                if (is_string($value) && !in_array($key, ['slug', 'dominio', 'email', 'url_foto'])) {
                    $this->$key = strtoupper($value);
                }
            }
        } else {
            $retorno = false;
            Yii::$app->session->setFlash('error', Yii::t('yii', Yii::$app->params['mmf2']), 0);
            $this->addError('id', Yii::t('yii', Yii::$app->params['mmf2']));
        }

        return $retorno;
    }

    /**
     * Operações executadas após localizar os dados
     */
    public function afterFind() {
        date_default_timezone_set(SisParamsController::getTimeZone());
        $this->matricula = str_pad($this->matricula, 8, '0', STR_PAD_LEFT);
    }

    /**
     * Retorna um model caso o servidor esteja falecido ou null caso não esteja
     * @return type
     */
    public function getFalecimento() {
        return CadSmovimentacao::find()->where([
                            'dominio' => Yii::$app->user->identity->dominio,
                            'id_cad_servidores' => $this->id,
                        ])->andWhere(['or', ['codigo_afastamento' => 'S2'], ['codigo_afastamento' => 'S3']])
                        ->orderBy('d_afastamento')
                        ->limit('1')
                        ->one();
    }

    /**
     * Retorna um model caso o servidor tenha uma ficha funcional no periodo
     * @return type 
     */
    public function getFinSfuncional() {
        if (($return = FinSfuncional::find()->where([
                    FinSfuncional::tableName() . ".id_cad_servidores" => $this->id,
                    FinSfuncional::tableName() . ".ano" => Yii::$app->user->identity->per_ano,
                    FinSfuncional::tableName() . ".mes" => Yii::$app->user->identity->per_mes,
                    FinSfuncional::tableName() . ".parcela" => Yii::$app->user->identity->per_parcela,
                    FinSfuncional::tableName() . ".dominio" => Yii::$app->user->identity->dominio,
                ])->one()) != null) {
            return $return;
        } else {
            return new FinSfuncional();
        }
    }

    public function getEnio() {
        if (($model = FinSfuncional::find()
                        ->where([
                            'id_cad_servidores' => $this->id,
                            'ano' => Yii::$app->user->identity->per_ano,
                            'mes' => Yii::$app->user->identity->per_mes,
                            'parcela' => Yii::$app->user->identity->per_parcela,
                        ])->one()) != null) {
            return $model->enio;
        } else {
            return 0;
        }
    }

}
