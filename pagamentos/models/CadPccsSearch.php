<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadPccs;

/**
 * CadPccsSearch represents the model behind the search form of `pagamentos\models\CadPccs`.
 */
class CadPccsSearch extends CadPccs {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'id_pccs', 'nome_pccs', 'nivel_pccs'], 'safe'],
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
        $query = CadPccs::find();

        // add conditions that should always apply here
        $query->andFilterWhere(['=', $this::tableName() . '.status', self::STATUS_ATIVO])
                ->andFilterWhere(['=', $this::tableName() . '.dominio', Yii::$app->user->identity->dominio]);

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
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.id_pccs', $this->id_pccs])
                ->andFilterWhere(['like', $this::tableName() . '.nome_pccs', $this->nome_pccs])
                ->andFilterWhere(['like', $this::tableName() . '.nivel_pccs', $this->nivel_pccs]);

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
