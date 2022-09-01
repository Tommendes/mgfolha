<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserOptions;

/**
 * UserOptionsSearch represents the model behind the search form of `common\models\UserOptions`.
 */
class UserOptionsSearch extends UserOptions {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'id_user', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['geo_lt', 'geo_ln', 'cliente'], 'safe'],
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
        $query = UserOptions::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->identity->administrador) {
            $query->andFilterWhere(['=', 'status', UserOptions::STATUS_ATIVO]);
        }

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
            'id_user' => $this->id_user,
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cliente' => $this->cliente,
        ]);

        $query->andFilterWhere(['like', 'geo_lt', $this->geo_lt])
                ->andFilterWhere(['like', 'geo_ln', $this->geo_ln]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = trim(strtotime($date_explode[0] . ' 00:00:00'));
            $date2 = trim(strtotime($date_explode[1] . ' 23:59:59'));
            $query->andFilterWhere(['between', 'UserOptions.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
