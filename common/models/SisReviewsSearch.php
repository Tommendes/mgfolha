<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SisReviews;

/**
 * SisReviewsSearch represents the model behind the search form of `common\models\SisReviews`.
 */
class SisReviewsSearch extends SisReviews {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'versao', 'lancamento', 'revisao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'titulo', 'descricao'], 'safe'],
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
        $query = SisReviews::find();

        // add conditions that should always apply here
        $query->filterWhere(['or',
            ['status' => SisReviews::STATUS_ATIVO],
            ['status' => SisReviews::STATUS_INATIVO],
        ]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([
                'versao' => SORT_DESC,
                'lancamento' => SORT_DESC,
                'revisao' => SORT_DESC,
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
            'id' => $this->id,
            'versao' => $this->versao,
            'lancamento' => $this->lancamento,
            'revisao' => $this->revisao,
            'status' => $this->status,
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'dominio', $this->dominio])
                ->andFilterWhere(['like', 'titulo', $this->titulo])
                ->andFilterWhere(['like', 'descricao', $this->descricao]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = trim(strtotime($date_explode[0] . ' 00:00:00'));
            $date2 = trim(strtotime($date_explode[1] . ' 23:59:59'));
            $query->andFilterWhere(['between', 'sisreviews.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
