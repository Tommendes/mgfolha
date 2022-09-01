<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinParametros;

/**
 * FinParametrosSearch represents the model behind the search form of `pagamentos\models\FinParametros`.
 */
class FinParametrosSearch extends FinParametros {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'situacao',
            'manad_tipofolha'], 'integer'],
            [['slug', 'dominio', 'ano', 'mes', 'parcela', 'ano_informacao',
            'mes_informacao', 'parcela_informacao', 'descricao', 'd_situacao', 'mensagem', 'mensagem_aniversario'], 'safe'],
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
    public function search($params) {
        $query = FinParametros::find();

        // add conditions that should always apply here

        $query->where([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([
                $this::tableName() . '.ano' => SORT_DESC,
                $this::tableName() . '.mes' => SORT_DESC,
                $this::tableName() . '.parcela' => SORT_DESC,
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
            $this::tableName() . '.situacao' => $this->situacao,
            $this::tableName() . '.manad_tipofolha' => $this->manad_tipofolha,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.ano', $this->ano])
                ->andFilterWhere(['like', $this::tableName() . '.mes', $this->mes])
                ->andFilterWhere(['like', $this::tableName() . '.parcela', $this->parcela])
                ->andFilterWhere(['like', $this::tableName() . '.ano_informacao', $this->ano_informacao])
                ->andFilterWhere(['like', $this::tableName() . '.mes_informacao', $this->mes_informacao])
                ->andFilterWhere(['like', $this::tableName() . '.parcela_informacao', $this->parcela_informacao])
                ->andFilterWhere(['like', $this::tableName() . '.descricao', $this->descricao])
                ->andFilterWhere(['like', $this::tableName() . '.d_situacao', $this->d_situacao])
                ->andFilterWhere(['like', $this::tableName() . '.mensagem', $this->mensagem])
                ->andFilterWhere(['like', $this::tableName() . '.mensagem_aniversario', $this->mensagem_aniversario]);

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
