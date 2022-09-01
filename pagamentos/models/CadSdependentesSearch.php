<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadSdependentes;

/**
 * CadSdependentesSearch represents the model behind the search form of `pagamentos\models\CadSdependentes`.
 */
class CadSdependentesSearch extends CadSdependentes {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at',
            'id_cad_servidores', 'matricula', 'tipo', 'permanente',
            'carteira_vacinacao', 'historico_escolar', 'plano_saude',
            'pensionista', 'irpf'], 'integer'],
            [['slug', 'dominio', 'nome', 'nascimento_d', 'inss_prz', 'irrf_prz',
            'rpps_prz', 'rg', 'rg_emissor', 'rg_uf', 'rg_d', 'certidao',
            'livro', 'folha', 'certidao_d', 'cpf', 'relacao_dependecia'], 'safe'],
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
        $query = CadSdependentes::find();

        // add conditions that should always apply here

        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);
        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([CadServidores::tableName() . '.nome' => SORT_ASC]);
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
            $this::tableName() . '.id' => $this->id,
            $this::tableName() . '.status' => $this->status,
            $this::tableName() . '.evento' => $this->evento,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.id_cad_servidores' => $this->id_cad_servidores,
            $this::tableName() . '.matricula' => $this->matricula,
            $this::tableName() . '.tipo' => $this->tipo,
            $this::tableName() . '.permanente' => $this->permanente,
            $this::tableName() . '.carteira_vacinacao' => $this->carteira_vacinacao,
            $this::tableName() . '.historico_escolar' => $this->historico_escolar,
            $this::tableName() . '.plano_saude' => $this->plano_saude,
            $this::tableName() . '.pensionista' => $this->pensionista,
            $this::tableName() . '.irpf' => $this->irpf,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.nome', $this->nome])
                ->andFilterWhere(['like', $this::tableName() . '.nascimento_d', $this->nascimento_d])
                ->andFilterWhere(['like', $this::tableName() . '.inss_prz', $this->inss_prz])
                ->andFilterWhere(['like', $this::tableName() . '.irrf_prz', $this->irrf_prz])
                ->andFilterWhere(['like', $this::tableName() . '.rpps_prz', $this->rpps_prz])
                ->andFilterWhere(['like', $this::tableName() . '.rg', $this->rg])
                ->andFilterWhere(['like', $this::tableName() . '.rg_emissor', $this->rg_emissor])
                ->andFilterWhere(['like', $this::tableName() . '.rg_uf', $this->rg_uf])
                ->andFilterWhere(['like', $this::tableName() . '.rg_d', $this->rg_d])
                ->andFilterWhere(['like', $this::tableName() . '.certidao', $this->certidao])
                ->andFilterWhere(['like', $this::tableName() . '.livro', $this->livro])
                ->andFilterWhere(['like', $this::tableName() . '.folha', $this->folha])
                ->andFilterWhere(['like', $this::tableName() . '.certidao_d', $this->certidao_d])
                ->andFilterWhere(['like', $this::tableName() . '.cpf', $this->cpf])
                ->andFilterWhere(['like', $this::tableName() . '.relacao_dependecia', $this->relacao_dependecia]);

        return $dataProvider;
    }

}
