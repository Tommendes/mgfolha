<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinRubricas;

/**
 * FinRubricasSearch represents the model behind the search form of `pagamentos\models\FinRubricas`.
 */
class FinRubricasSearch extends FinRubricas {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores', 'id_fin_eventos', 'prazo', 'prazot'], 'integer'],
            [['slug', 'dominio', 'ano', 'mes', 'parcela', 'referencia'], 'safe'],
            [['valor_baseespecial', 'valor_base', 'valor_basefixa', 'valor_desconto', 'valor_percentual', 'valor_saldo', 'valor', 'valor_patronal', 'valor_maternidade'], 'number'],
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
    public function search($params, $matricula) {
        $query = FinRubricas::find();

        // add conditions that should always apply here
        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->join('join', FinEventos::tableName(), FinEventos::tableName() . '.id = ' . self::tableName() . '.id_fin_eventos');
        $query->where([CadServidores::tableName() . '.matricula' => $matricula]);
//        $query->andWhere(['>', 'valor', 0]);
        $query->andWhere([
            $this::tableName() . '.dominio' => Yii::$app->user->identity->dominio,
            $this::tableName() . '.ano' => Yii::$app->user->identity->per_ano,
            $this::tableName() . '.mes' => Yii::$app->user->identity->per_mes,
            $this::tableName() . '.parcela' => Yii::$app->user->identity->per_parcela,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([FinEventos::tableName() . '.id_evento' => SORT_ASC]);
        }

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
            $this::tableName() . '.id_fin_eventos' => $this->id_fin_eventos,
            $this::tableName() . '.valor_baseespecial' => $this->valor_baseespecial,
            $this::tableName() . '.valor_base' => $this->valor_base,
            $this::tableName() . '.valor_basefixa' => $this->valor_basefixa,
            $this::tableName() . '.valor_desconto' => $this->valor_desconto,
            $this::tableName() . '.valor_percentual' => $this->valor_percentual,
            $this::tableName() . '.valor_saldo' => $this->valor_saldo,
            $this::tableName() . '.valor' => $this->valor,
            $this::tableName() . '.valor_patronal' => $this->valor_patronal,
            $this::tableName() . '.valor_maternidade' => $this->valor_maternidade,
            $this::tableName() . '.prazo' => $this->prazo,
            $this::tableName() . '.prazot' => $this->prazot,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.ano', $this->ano])
                ->andFilterWhere(['like', $this::tableName() . '.mes', $this->mes])
                ->andFilterWhere(['like', $this::tableName() . '.parcela', $this->parcela])
                ->andFilterWhere(['like', $this::tableName() . '.referencia', $this->referencia]);

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
