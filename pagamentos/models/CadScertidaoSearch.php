<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadScertidao;
use pagamentos\models\CadServidores;

/**
 * CadScertidaoSearch represents the model behind the search form of `pagamentos\models\CadScertidao`.
 */
class CadScertidaoSearch extends CadScertidao {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores', 'tipo'], 'integer'],
            [['certidao', 'emissao', 'cartorio', 'uf', 'cidade', 'termo', 'livro', 'folha'], 'safe'],
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
        $query = CadScertidao::find();

        // add conditions that should always apply here
        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([CadServidores::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

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
            $this::tableName() . '.tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.certidao', $this->certidao])
                ->andFilterWhere(['like', $this::tableName() . '.emissao', $this->emissao])
                ->andFilterWhere(['like', $this::tableName() . '.cartorio', $this->cartorio])
                ->andFilterWhere(['like', $this::tableName() . '.uf', $this->uf])
                ->andFilterWhere(['like', $this::tableName() . '.cidade', $this->cidade])
                ->andFilterWhere(['like', $this::tableName() . '.termo', $this->termo])
                ->andFilterWhere(['like', $this::tableName() . '.livro', $this->livro])
                ->andFilterWhere(['like', $this::tableName() . '.folha', $this->folha]);

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
