<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinSfuncional;

/**
 * FinSfuncionalSearch represents the model behind the search form of `pagamentos\models\FinSfuncional`.
 */
class FinSfuncionalSearch extends FinSfuncional {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'id_cad_servidores', 'situacao',
            'situacaofuncional', 'id_cad_cargos', 'id_cad_centros', 'id_cad_departamentos',
            'id_pccs', 'desconta_irrf', 'tp_previdencia', 'desconta_sindicato',//'desconta_inss', 'desconta_rpps',
            'lanca_anuenio', 'lanca_trienio', 'lanca_quinquenio', 'lanca_decenio',
            'lanca_salario', 'lanca_funcao', 'n_faltas', 'decimo_aniv', 'n_horaaula',
            'n_adnoturno', 'n_hextra', 'previdencia', 'tipobeneficio', 'ponto', 'enio'], 'integer'],
            [['slug', 'dominio', 'ano', 'mes', 'parcela', 'categoria_receita', 'd_beneficio',
            'retorno_ocorrencia', 'retorno_data', 'retorno_documento'], 'safe'],
            [['retorno_valor'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $id_cad_servidores) {
        $query = FinSfuncional::find();
        $mes = Yii::$app->user->identity->per_mes;
        $ano = Yii::$app->user->identity->per_ano;
        $tableName = $this::tableName();

        // add conditions that should always apply here

        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([
            $tableName . '.dominio' => Yii::$app->user->identity->dominio,
            $tableName . '.parcela' => Yii::$app->user->identity->per_parcela,
        ]);
        $query->andWhere("LAST_DAY(CONCAT($tableName.ano, '/', $tableName.mes, '/01')) <= LAST_DAY('$ano/$mes/01')");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            $this::tableName() . '.id' => $this->id,
            $this::tableName() . '.status' => $this->status,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.id_cad_servidores' => $this->id_cad_servidores,
            $this::tableName() . '.situacao' => $this->situacao,
            $this::tableName() . '.situacaofuncional' => $this->situacaofuncional,
            $this::tableName() . '.id_cad_cargos' => $this->id_cad_cargos,
            $this::tableName() . '.id_cad_centros' => $this->id_cad_centros,
            $this::tableName() . '.id_cad_departamentos' => $this->id_cad_departamentos,
            $this::tableName() . '.id_pccs' => $this->id_pccs,
            $this::tableName() . '.desconta_irrf' => $this->desconta_irrf,
            $this::tableName() . '.tp_previdencia' => $this->tp_previdencia,
//            $this::tableName() . '.desconta_inss' => $this->desconta_inss,
//            $this::tableName() . '.desconta_rpps' => $this->desconta_rpps,
            $this::tableName() . '.desconta_sindicato' => $this->desconta_sindicato,
            $this::tableName() . '.lanca_anuenio' => $this->lanca_anuenio,
            $this::tableName() . '.lanca_trienio' => $this->lanca_trienio,
            $this::tableName() . '.lanca_quinquenio' => $this->lanca_quinquenio,
            $this::tableName() . '.lanca_decenio' => $this->lanca_decenio,
            $this::tableName() . '.lanca_salario' => $this->lanca_salario,
            $this::tableName() . '.lanca_funcao' => $this->lanca_funcao,
            $this::tableName() . '.n_faltas' => $this->n_faltas,
            $this::tableName() . '.decimo_aniv' => $this->decimo_aniv,
            $this::tableName() . '.n_horaaula' => $this->n_horaaula,
            $this::tableName() . '.n_adnoturno' => $this->n_adnoturno,
            $this::tableName() . '.n_hextra' => $this->n_hextra,
            $this::tableName() . '.previdencia' => $this->previdencia,
            $this::tableName() . '.tipobeneficio' => $this->tipobeneficio,
            $this::tableName() . '.retorno_valor' => $this->retorno_valor,
            $this::tableName() . '.ponto' => $this->ponto,
            $this::tableName() . '.enio' => $this->enio,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.ano', $this->ano])
                ->andFilterWhere(['like', $this::tableName() . '.mes', $this->mes])
                ->andFilterWhere(['like', $this::tableName() . '.parcela', $this->parcela])
                ->andFilterWhere(['like', $this::tableName() . '.categoria_receita', $this->categoria_receita])
                ->andFilterWhere(['like', $this::tableName() . '.d_beneficio', $this->d_beneficio])
                ->andFilterWhere(['like', $this::tableName() . '.retorno_ocorrencia', $this->retorno_ocorrencia])
                ->andFilterWhere(['like', $this::tableName() . '.retorno_data', $this->retorno_data])
                ->andFilterWhere(['like', $this::tableName() . '.retorno_documento', $this->retorno_documento]);

////         filtrar por período
//        if (isset($this->created_at) && $this->created_at != '') {
//            $date_explode = explode(" - ", $this->created_at);
//            $date1 = strtotime($date_explode[0] . ' 00:00:00');
//            $date2 = strtotime($date_explode[1] . ' 23:59:59');
//            $query->andFilterWhere(['between', 'sis_events.created_at', $date1, $date2]);
//        }

        return $dataProvider;
    }

}
