<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Msgs;

/**
 * MsgsSearch represents the model behind the search form of `frontend\models\Msgs`.
 */
class MsgsSearch extends Msgs {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'id_pos', 'id_desk_user', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'caption', 'body', 'link', 'caption_color_id'], 'safe'],
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
        $query = Msgs::find();

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
            'id_desk_user' => $this->id_desk_user,
            'id_pos' => $this->id_pos,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'dominio', $this->dominio])
                ->andFilterWhere(['like', 'caption', $this->caption])
                ->andFilterWhere(['like', 'caption_color_id', $this->caption_color_id])
                ->andFilterWhere(['like', 'link', $this->link])
                ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }

}
