<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrgaoUa;

/**
 * OrgaoUaSearch represents the model behind the search form of `common\models\OrgaoUa`.
 */
class OrgaoUaSearch extends OrgaoUa {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'id_orgao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['codigo', 'cnpj', 'nome'], 'safe'],
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
    public function search($params, $id_orgao) {
        $query = OrgaoUa::find();

        // add conditions that should always apply here
        $query->join('join', 'orgao', 'orgao.id = orgao_ua.id_orgao');
        $query->andFilterWhere(['=', $this::tableName() . '.status', self::STATUS_ATIVO])
                ->andFilterWhere(['=', 'orgao.dominio', Yii::$app->user->identity->dominio]);

        if (!$id_orgao == null) {
            $query->andFilterWhere(['=', 'id_orgao', $id_orgao]);
        } else {
            $query->where('0=1');
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
            'orgao_ua.id' => $this->id,
            'orgao_ua.id_orgao' => $this->id_orgao,
            'orgao_ua.status' => $this->status,
            'orgao_ua.evento' => $this->evento,
            'orgao_ua.created_at' => $this->created_at,
            'orgao_ua.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'orgao_ua.codigo', $this->codigo])
                ->andFilterWhere(['like', 'orgao_ua.cnpj', $this->cnpj])
                ->andFilterWhere(['like', 'orgao_ua.nome', $this->nome]);

        return $dataProvider;
    }

}
