<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadBconvenios;

/**
 * CadBconveniosSearch represents the model behind the search form of `pagamentos\models\CadBconvenios`.
 */
class CadBconveniosSearch extends CadBconvenios {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_cad_bancos', 'id_orgao_ua', 'tipo_servico'], 'integer'],
            [['slug', 'dominio', 'convenio', 'cod_compromisso', 'agencia', 'a_digito', 'conta', 'c_digito', 'convenio_nr'], 'safe'],
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
        $query = CadBconvenios::find();

        // add conditions that should always apply here

        $query->join('join', 'orgao_ua', 'orgao_ua.id = ' . $this::tableName() . '.id_orgao_ua');
        $query->join('join', 'orgao', 'orgao.id = orgao_ua.id_orgao');
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
            $this::tableName() . '.id' => $this->id,
            $this::tableName() . '.status' => $this->status,
            $this::tableName() . '.evento' => $this->evento,
            $this::tableName() . '.created_at' => $this->created_at,
            $this::tableName() . '.updated_at' => $this->updated_at,
            $this::tableName() . '.id_cad_bancos' => $this->id_cad_bancos,
            $this::tableName() . '.id_orgao_ua' => $this->id_orgao_ua,
            $this::tableName() . '.tipo_servico' => $this->tipo_servico,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.convenio', $this->convenio])
                ->andFilterWhere(['like', $this::tableName() . '.cod_compromisso', $this->cod_compromisso])
                ->andFilterWhere(['like', $this::tableName() . '.agencia', $this->agencia])
                ->andFilterWhere(['like', $this::tableName() . '.a_digito', $this->a_digito])
                ->andFilterWhere(['like', $this::tableName() . '.conta', $this->conta])
                ->andFilterWhere(['like', $this::tableName() . '.c_digito', $this->c_digito])
                ->andFilterWhere(['like', $this::tableName() . '.convenio_nr', $this->convenio_nr]);

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
