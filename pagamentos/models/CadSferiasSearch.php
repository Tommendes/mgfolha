<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadSferias;

/**
 * CadSferiasSearch represents the model behind the search form of `pagamentos\models\CadSferias`.
 */
class CadSferiasSearch extends CadSferias {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'id_cad_servidores', 'status', 'evento', 'created_at', 'updated_at', 'periodo'], 'integer'],
            [['slug', 'dominio', 'd_ferias', 'observacoes'], 'safe'],
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
    public function search($params, $id_cad_servidores) {
        $query = CadSferias::find();

        // add conditions that should always apply here

        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

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
            $this::tableName() . '.id_cad_servidores' => $this->id_cad_servidores,
            $this::tableName() . '.status' => $this->status,
            $this::tableName() . '.evento' => $this->evento,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.periodo' => $this->periodo,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.d_ferias', $this->d_ferias])
                ->andFilterWhere(['like', $this::tableName() . '.observacoes', $this->observacoes]);

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
