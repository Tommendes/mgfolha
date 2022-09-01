<?php

namespace pagamentos\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\DeskUsers;

/**
 * DeskUsersSearch represents the model behind the search form of `pagamentos\models\DeskUsers`.
 */
class DeskUsersSearch extends DeskUsers {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'status_desk', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['dll_cod', 'dll_cnpj', 'dll_nome', 'cli_cnpj', 'cli_nome', 'cli_nome_comput', 'cli_nome_user', 'versao_desk'], 'safe'],
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
        $query = DeskUsers::find();

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
            $this::tableName() . '.status' => $this->status,
            $this::tableName() . '.status_desk' => $this->status_desk,
            $this::tableName() . '.evento' => $this->evento,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.dll_cod', $this->dll_cod])
                ->andFilterWhere(['like', $this::tableName() . '.dll_cnpj', $this->dll_cnpj])
                ->andFilterWhere(['like', $this::tableName() . '.dll_nome', $this->dll_nome])
                ->andFilterWhere(['like', $this::tableName() . '.cli_cnpj', $this->cli_cnpj])
                ->andFilterWhere(['like', $this::tableName() . '.cli_nome', $this->cli_nome])
                ->andFilterWhere(['like', $this::tableName() . '.cli_nome_comput', $this->cli_nome_comput])
                ->andFilterWhere(['like', $this::tableName() . '.cli_nome_user', $this->cli_nome_user])
                ->andFilterWhere(['like', $this::tableName() . '.versao_desk', $this->versao_desk]);

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
