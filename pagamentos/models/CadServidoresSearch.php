<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadServidores;

/**
 * CadServidoresSearch represents the model behind the search form of `pagamentos\models\CadServidores`.
 */
class CadServidoresSearch extends CadServidores {

    public $mixNome;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at',], 'integer'],
            [['slug', 'dominio', 'url_foto', 'matricula', 'nome', 'cpf', 'rg', 'rg_emissor',
            'rg_uf', 'rg_d', 'pispasep', 'pispasep_d', 'titulo', 'titulosecao',
            'titulozona', 'ctps', 'ctps_serie', 'ctps_uf', 'ctps_d', 'nascimento_d',
            'pai', 'mae', 'cep', 'logradouro', 'numero', 'complemento', 'bairro',
            'cidade', 'uf', 'naturalidade', 'naturalidade_uf', 'telefone', 'celular',
            'email', 'banco_agencia', 'banco_agencia_digito', 'banco_conta',
            'banco_conta_digito', 'banco_operacao', 'nacionalidade',
            'idbanco', 'sexo', 'raca', 'estado_civil', 'tipodeficiencia', 'd_admissao', 'mixNome'], 'safe'],
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
        $query = CadServidores::find();

        // add conditions that should always apply here
        $query->where([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([$this::tableName() . '.nome' => SORT_ASC]);
        }

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
            $this::tableName() . '.idbanco' => $this->idbanco,
            $this::tableName() . '.sexo' => $this->sexo,
            $this::tableName() . '.raca' => $this->raca,
            $this::tableName() . '.estado_civil' => $this->estado_civil,
            $this::tableName() . '.tipodeficiencia' => $this->tipodeficiencia,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.url_foto', $this->url_foto])
                ->andFilterWhere(['like', $this::tableName() . '.nome', $this->nome])
                ->andFilterWhere(['like', $this::tableName() . '.matricula', isset($this->matricula) && $this->matricula != '' ? intval($this->matricula) : $this->matricula])
                ->andFilterWhere(['like', $this::tableName() . '.cpf', str_replace('.', '', str_replace('-', '', $this->cpf))])
                ->andFilterWhere(['like', $this::tableName() . '.rg', $this->rg])
                ->andFilterWhere(['like', $this::tableName() . '.rg_emissor', $this->rg_emissor])
                ->andFilterWhere(['like', $this::tableName() . '.rg_uf', $this->rg_uf])
                ->andFilterWhere(['like', $this::tableName() . '.rg_d', $this->rg_d])
                ->andFilterWhere(['like', $this::tableName() . '.pispasep', $this->pispasep])
                ->andFilterWhere(['like', $this::tableName() . '.pispasep_d', $this->pispasep_d])
                ->andFilterWhere(['like', $this::tableName() . '.titulo', $this->titulo])
                ->andFilterWhere(['like', $this::tableName() . '.titulosecao', $this->titulosecao])
                ->andFilterWhere(['like', $this::tableName() . '.titulozona', $this->titulozona])
                ->andFilterWhere(['like', $this::tableName() . '.ctps', $this->ctps])
                ->andFilterWhere(['like', $this::tableName() . '.ctps_serie', $this->ctps_serie])
                ->andFilterWhere(['like', $this::tableName() . '.ctps_uf', $this->ctps_uf])
                ->andFilterWhere(['like', $this::tableName() . '.ctps_d', $this->ctps_d])
                ->andFilterWhere(['like', $this::tableName() . '.nascimento_d', $this->nascimento_d])
                ->andFilterWhere(['like', $this::tableName() . '.pai', $this->pai])
                ->andFilterWhere(['like', $this::tableName() . '.mae', $this->mae])
                ->andFilterWhere(['like', $this::tableName() . '.cep', $this->cep])
                ->andFilterWhere(['like', $this::tableName() . '.logradouro', $this->logradouro])
                ->andFilterWhere(['like', $this::tableName() . '.numero', $this->numero])
                ->andFilterWhere(['like', $this::tableName() . '.complemento', $this->complemento])
                ->andFilterWhere(['like', $this::tableName() . '.bairro', $this->bairro])
                ->andFilterWhere(['like', $this::tableName() . '.cidade', $this->cidade])
                ->andFilterWhere(['like', $this::tableName() . '.uf', $this->uf])
                ->andFilterWhere(['like', $this::tableName() . '.naturalidade', $this->naturalidade])
                ->andFilterWhere(['like', $this::tableName() . '.naturalidade_uf', $this->naturalidade_uf])
                ->andFilterWhere(['like', $this::tableName() . '.telefone', $this->telefone])
                ->andFilterWhere(['like', $this::tableName() . '.celular', $this->celular])
                ->andFilterWhere(['like', $this::tableName() . '.email', $this->email])
                ->andFilterWhere(['like', $this::tableName() . '.banco_agencia', $this->banco_agencia])
                ->andFilterWhere(['like', $this::tableName() . '.banco_agencia_digito', $this->banco_agencia_digito])
                ->andFilterWhere(['like', $this::tableName() . '.banco_conta', $this->banco_conta])
                ->andFilterWhere(['like', $this::tableName() . '.banco_conta_digito', $this->banco_conta_digito])
                ->andFilterWhere(['like', $this::tableName() . '.banco_operacao', $this->banco_operacao])
                ->andFilterWhere(['like', $this::tableName() . '.nacionalidade', $this->nacionalidade])
                ->andFilterWhere(['like', $this::tableName() . '.d_admissao', $this->d_admissao]);
        $this->mixNome = trim($this->mixNome);
        $query->andFilterWhere(['or',
            ['like', $this::tableName() . '.nome', $this->mixNome],
            ['like', $this::tableName() . '.matricula', $this->mixNome],
            ['like', $this::tableName() . '.cpf', $this->mixNome],
            ['like', $this::tableName() . '.cpf', str_replace('.', '', str_replace('-', '', $this->mixNome))]
        ]);

        return $dataProvider;
    }

}
