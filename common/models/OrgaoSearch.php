<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orgao;

/**
 * OrgaoSearch represents the model behind the search form of `common\models\Orgao`.
 */
class OrgaoSearch extends Orgao {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at'], 'integer'],
            [['slug', 'dominio', 'orgao', 'cnpj', 'url_logo', 'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'email', 'telefone', 'codigo_fpas', 'codigo_gps', 'codigo_cnae', 'codigo_ibge', 'codigo_fgts', 'mes_descsindical', 'cpf_responsavel_dirf', 'nome_responsavel_dirf'], 'safe'],
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
        $query = Orgao::find();

        // add conditions that should always apply here
        $query->andFilterWhere(['=', 'dominio', isset(Yii::$app->user->identity->dominio) ?
                    Yii::$app->user->identity->dominio : '0']);

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
            'evento' => $this->evento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', 'dominio', $this->dominio])
                ->andFilterWhere(['like', 'orgao', $this->orgao])
                ->andFilterWhere(['like', 'cnpj', $this->cnpj])
                ->andFilterWhere(['like', 'url_logo', $this->url_logo])
                ->andFilterWhere(['like', 'cep', $this->cep])
                ->andFilterWhere(['like', 'logradouro', $this->logradouro])
                ->andFilterWhere(['like', 'numero', $this->numero])
                ->andFilterWhere(['like', 'complemento', $this->complemento])
                ->andFilterWhere(['like', 'bairro', $this->bairro])
                ->andFilterWhere(['like', 'cidade', $this->cidade])
                ->andFilterWhere(['like', 'uf', $this->uf])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'telefone', $this->telefone])
                ->andFilterWhere(['like', 'codigo_fpas', $this->codigo_fpas])
                ->andFilterWhere(['like', 'codigo_gps', $this->codigo_gps])
                ->andFilterWhere(['like', 'codigo_cnae', $this->codigo_cnae])
                ->andFilterWhere(['like', 'codigo_ibge', $this->codigo_ibge])
                ->andFilterWhere(['like', 'codigo_fgts', $this->codigo_fgts])
                ->andFilterWhere(['like', 'mes_descsindical', $this->mes_descsindical])
                ->andFilterWhere(['like', 'cpf_responsavel_dirf', $this->cpf_responsavel_dirf])
                ->andFilterWhere(['like', 'nome_responsavel_dirf', $this->nome_responsavel_dirf]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = trim(strtotime($date_explode[0] . ' 00:00:00'));
            $date2 = trim(strtotime($date_explode[1] . ' 23:59:59'));
            $query->andFilterWhere(['between', 'orgao.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
