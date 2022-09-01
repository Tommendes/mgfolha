<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrgaoResp;

/**
 * OrgaoRespSearch represents the model behind the search form of `common\models\OrgaoResp`.
 */
class OrgaoRespSearch extends OrgaoResp {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'id_orgao', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['cpf_gestor', 'nome_gestor', 'd_nascimento', 'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'email', 'telefone'], 'safe'],
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
        $query = OrgaoResp::find();

        // add conditions that should always apply here
        $query->join('join', 'orgao', 'orgao.id = orgao_resp.id_orgao');
        $query->andFilterWhere(['=', $this::tableName() . '.status', self::STATUS_ATIVO])
                ->andFilterWhere(['=', 'orgao.dominio', Yii::$app->user->identity->dominio]);

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
            'id_orgao' => $this->id_orgao,
            'status' => $this->status,
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'cpf_gestor', $this->cpf_gestor])
                ->andFilterWhere(['like', 'nome_gestor', $this->nome_gestor])
                ->andFilterWhere(['like', 'd_nascimento', $this->d_nascimento])
                ->andFilterWhere(['like', 'cep', $this->cep])
                ->andFilterWhere(['like', 'logradouro', $this->logradouro])
                ->andFilterWhere(['like', 'numero', $this->numero])
                ->andFilterWhere(['like', 'complemento', $this->complemento])
                ->andFilterWhere(['like', 'bairro', $this->bairro])
                ->andFilterWhere(['like', 'cidade', $this->cidade])
                ->andFilterWhere(['like', 'uf', $this->uf])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'telefone', $this->telefone]);

        return $dataProvider;
    }

}
