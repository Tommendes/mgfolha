<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SisParams;

/**
 * SisParamsSearch represents the model behind the search form of `common\models\SisParams`.
 */
class SisParamsSearch extends SisParams {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'grupo', 'parametro', 'label', 'data_registro'], 'safe'],
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
        $query = SisParams::find();

        // add conditions that should always apply here
        $query->andFilterWhere(['or',
                    ['dominio' => isset(Yii::$app->user->identity->dominio) ?
                                Yii::$app->user->identity->dominio : '0'],
                    ['dominio' => Yii::$app->id],])
                ->andFilterWhere(['=', 'status', SisParams::STATUS_ATIVO]);

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
            'data_registro' => $this->data_registro,
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'dominio', $this->dominio])
                ->andFilterWhere(['like', 'grupo', $this->grupo])
                ->andFilterWhere(['like', 'parametro', $this->parametro])
                ->andFilterWhere(['like', 'label', $this->label]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = trim(strtotime($date_explode[0] . ' 00:00:00'));
            $date2 = trim(strtotime($date_explode[1] . ' 23:59:59'));
            $query->andFilterWhere(['between', 'params.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
