<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadCidades;

/**
 * CadCidadesSearch represents the model behind the search form of `pagamentos\models\CadCidades`.
 */
class CadCidadesSearch extends CadCidades {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'uf_nome', 'uf_id', 'uf_abrev', 'municipio_id', 'municipio_nome', 'data_registro'], 'safe'],
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
        $query = CadCidades::find();

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
            $this::tableName() . '.data_registro' => $this->data_registro,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.uf_nome', $this->uf_nome])
                ->andFilterWhere(['like', $this::tableName() . '.uf_id', $this->uf_id])
                ->andFilterWhere(['like', $this::tableName() . '.uf_abrev', $this->uf_abrev])
                ->andFilterWhere(['like', $this::tableName() . '.municipio_id', $this->municipio_id])
                ->andFilterWhere(['like', $this::tableName() . '.municipio_nome', $this->municipio_nome]);

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
