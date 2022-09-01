<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadClasses;

/**
 * CadClassesSearch represents the model behind the search form of `pagamentos\models\CadClasses`.
 */
class CadClassesSearch extends CadClasses {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_pccs', 'id_classe', 'i_ano_inicial', 'i_ano_final'], 'integer'],
            [['slug', 'dominio', 'nome_classe'], 'safe'],
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
        $query = CadClasses::find();

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
            $this::tableName() . '.id_pccs' => $this->id_pccs,
            $this::tableName() . '.id_classe' => $this->id_classe,
            $this::tableName() . '.i_ano_inicial' => $this->i_ano_inicial,
            $this::tableName() . '.i_ano_final' => $this->i_ano_final,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.nome_classe', $this->nome_classe]);

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
