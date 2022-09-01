<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadSmovimentacao;

/**
 * CadSmovimentacaoSearch represents the model behind the search form of `pagamentos\models\CadSmovimentacao`.
 */
class CadSmovimentacaoSearch extends CadSmovimentacao {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores'], 'integer'],
            [['slug', 'dominio', 'codigo_afastamento', 'd_afastamento', 'codigo_retorno', 'd_retorno', 'motivo_desligamentorais'], 'safe'],
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
        $query = CadSmovimentacao::find();

        // add conditions that should always apply here

        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy(['STR_TO_DATE(cad_smovimentacao.d_afastamento, \'%d/%m/%Y\')' => SORT_DESC]);
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
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.codigo_afastamento', $this->codigo_afastamento])
                ->andFilterWhere(['like', $this::tableName() . '.d_afastamento', $this->d_afastamento])
                ->andFilterWhere(['like', $this::tableName() . '.codigo_retorno', $this->codigo_retorno])
                ->andFilterWhere(['like', $this::tableName() . '.d_retorno', $this->d_retorno])
                ->andFilterWhere(['like', $this::tableName() . '.motivo_desligamentorais', $this->motivo_desligamentorais]);

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
