<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\FinEventospercentual;

/**
 * FinEventospercentualSearch represents the model behind the search form of `pagamentos\models\FinEventospercentual`.
 */
class FinEventospercentualSearch extends FinEventospercentual {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_fin_eventos'], 'integer'],
            [['slug', 'dominio', 'id_eventos_percentual', 'data'], 'safe'],
            [['percentual'], 'number'],
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
    public function search($params, $id_fin_eventos = null) {
        $query = FinEventospercentual::find();
        $evts_tbl = 'a';

        // add conditions that should always apply here
        $query->join('join', FinEventos::tableName() . ' ' . $evts_tbl, $evts_tbl . '.id = ' . self::tableName() . '.id_fin_eventos');
        $query->andWhere([$this::tableName() . '.dominio' => Yii::$app->user->identity->dominio]);
        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy([
                $this::tableName() . '.id_eventos_percentual' => SORT_DESC,
            ]);
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
            $this::tableName() . '.percentual' => $this->percentual,
//            $this::tableName() . '.id_fin_eventos' => $this->id_fin_eventos,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.id_eventos_percentual', $this->id_eventos_percentual])
                ->andFilterWhere(['like', $this::tableName() . '.data', $this->data]);
        if ($id_fin_eventos == null) {
            $query->andFilterWhere(['or',
                ['like', $evts_tbl . '.evento_nome', $this->id_fin_eventos],
                ['=', $evts_tbl . '.id', $this->id_fin_eventos],
            ]);
        } else {
            $query->andFilterWhere([
                $this::tableName() . '.id_fin_eventos' => $id_fin_eventos,
            ]);
        }

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
