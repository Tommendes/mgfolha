<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinFaixas;

/**
 * FinFaixasSearch represents the model behind the search form of `pagamentos\models\FinFaixas`.
 */
class FinFaixasSearch extends FinFaixas {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'tipo'], 'integer'],
            [['slug', 'dominio', 'data', 'faixa'], 'safe'],
            [['v_final1', 'v_faixa1', 'v_deduzir1', 'v_final2', 'v_faixa2',
            'v_deduzir2', 'v_final3', 'v_faixa3', 'v_deduzir3', 'v_final4',
            'v_faixa4', 'v_deduzir4', 'v_final5', 'v_faixa5', 'v_deduzir5',
            'deduzir_dependente', 'salario_vigente', 'inss_teto', 'inss_patronal',
            'inss_rat', 'inss_fap', 'rpps_patronal'], 'number'],
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
    public function search($params, $tipo = null) {
        $query = FinFaixas::find();

        // add conditions that should always apply here
        $query->where([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

        if ($tipo != null) {
            $query->andWhere([$this::tableName() . '.tipo' => $tipo]);
        }

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([
                $this::tableName() . '.tipo' => SORT_ASC,
                "DATE_FORMAT(STR_TO_DATE(" . $this::tableName() . ".data, '%d/%m/%Y'), '%Y-%m-%d')" => SORT_DESC,
                $this::tableName() . '.faixa' => SORT_ASC,
            ]);
        }

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
            $this::tableName() . '.evento' => $this->evento,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.tipo' => $this->tipo,
            $this::tableName() . '.v_final1' => $this->v_final1,
            $this::tableName() . '.v_faixa1' => $this->v_faixa1,
            $this::tableName() . '.v_deduzir1' => $this->v_deduzir1,
            $this::tableName() . '.v_final2' => $this->v_final2,
            $this::tableName() . '.v_faixa2' => $this->v_faixa2,
            $this::tableName() . '.v_deduzir2' => $this->v_deduzir2,
            $this::tableName() . '.v_final3' => $this->v_final3,
            $this::tableName() . '.v_faixa3' => $this->v_faixa3,
            $this::tableName() . '.v_deduzir3' => $this->v_deduzir3,
            $this::tableName() . '.v_final4' => $this->v_final4,
            $this::tableName() . '.v_faixa4' => $this->v_faixa4,
            $this::tableName() . '.v_deduzir4' => $this->v_deduzir4,
            $this::tableName() . '.v_final5' => $this->v_final5,
            $this::tableName() . '.v_faixa5' => $this->v_faixa5,
            $this::tableName() . '.v_deduzir5' => $this->v_deduzir5,
            $this::tableName() . '.deduzir_dependente' => $this->deduzir_dependente,
            $this::tableName() . '.salario_vigente' => $this->salario_vigente,
            $this::tableName() . '.inss_teto' => $this->inss_teto,
            $this::tableName() . '.inss_patronal' => $this->inss_patronal,
            $this::tableName() . '.inss_rat' => $this->inss_rat,
            $this::tableName() . '.inss_fap' => $this->inss_fap,
            $this::tableName() . '.rpps_patronal' => $this->rpps_patronal,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
//                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.data', $this->data])
                ->andFilterWhere(['like', $this::tableName() . '.faixa', $this->faixa]);

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
