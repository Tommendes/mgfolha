<?php

namespace cash\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use cash\models\Suporte;

/**
 * SuporteSearch represents the model behind the search form of `s\models\Suporte`.
 */
class SuporteSearch extends Suporte {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['dominio', 'grupo', 'slug', 'descricao', 'texto'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Suporte::find();

        // add conditions that should always apply here

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy(['id' => SORT_DESC]);
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
            'id' => $this->id,
            'status' => $this->status,
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'dominio', $this->dominio])
                ->andFilterWhere(['like', 'grupo', $this->grupo])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'descricao', $this->descricao])
                ->andFilterWhere(['like', 'texto', $this->texto]);

        return $dataProvider;
    }

}
