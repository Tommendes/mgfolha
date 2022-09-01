<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserDomains;

/**
 * UserDomainsSearch represents the model behind the search form of `common\models\UserDomains`.
 */
class UserDomainsSearch extends UserDomains {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['slug', 'base_servico', 'municipio', 'cliente', 'dominio'], 'safe'],
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
        $query = UserDomains::find();

        // add conditions that should always apply here

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
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.base_servico', $this->base_servico])
                ->andFilterWhere(['like', $this::tableName() . '.municipio', $this->municipio])
                ->andFilterWhere(['like', $this::tableName() . '.cliente', $this->cliente])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio]);

////         filtrar por período
//        if (isset($this->created_at) && $this->created_at != '') {
//            $date_explode = explode(" - ", $this->created_at);
//            $date1 = strtotime($date_explode[0] . ' 00:00:00');
//            $date2 = strtotime($date_explode[1] . ' 23:59:59');
//            $query->andFilterWhere(['between', 'eventos.created_at', $date1, $date2]);
//        }

        return $dataProvider;
    }

}
