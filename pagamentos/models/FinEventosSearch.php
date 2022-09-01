<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinEventos;

/**
 * FinEventosSearch represents the model behind the search form of `pagamentos\models\FinEventos`.
 */
class FinEventosSearch extends FinEventos {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'consignado', 'consignavel',
            'deduzconsig', 'automatico', 'i_prioridade', 'fixo', 'sefip', 'rais', 'ev_root'], 'integer'],
            [['slug', 'dominio', 'id_evento', 'evento_nome', 'tipo', 'vinculacao_dirf'], 'safe'],
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
        $query = FinEventos::find();

        // add conditions that should always apply here

        $query->where([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([$this::tableName() . '.id_evento' => SORT_ASC]);
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
            $this::tableName() . '.consignado' => $this->consignado,
            $this::tableName() . '.consignavel' => $this->consignavel,
            $this::tableName() . '.deduzconsig' => $this->deduzconsig,
            $this::tableName() . '.automatico' => $this->automatico,
            $this::tableName() . '.i_prioridade' => $this->i_prioridade,
            $this::tableName() . '.fixo' => $this->fixo,
            $this::tableName() . '.sefip' => $this->sefip,
            $this::tableName() . '.rais' => $this->rais,
            $this::tableName() . '.ev_root' => $this->ev_root,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.id_evento', $this->id_evento])
                ->andFilterWhere(['like', $this::tableName() . '.evento_nome', $this->evento_nome])
                ->andFilterWhere(['like', $this::tableName() . '.tipo', $this->tipo])
                ->andFilterWhere(['like', $this::tableName() . '.vinculacao_dirf', $this->vinculacao_dirf]);

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
