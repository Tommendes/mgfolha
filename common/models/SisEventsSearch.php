<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SisEventsSearch represents the model behind the search form of `common\models\SisEvents`.
 */
class SisEventsSearch extends SisEvents {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'updated_at', 'id_registro'], 'integer'],
            [['slug', 'dominio', 'evento', 'classevento', 'tabela_bd',
            'ip', 'geo_lt', 'geo_ln', 'id_user', 'created_at',], 'safe'],
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
        $query = SisEvents::find();

        // add conditions that should always apply here
        $query->join('JOIN', \common\models\User::tableName(), 'user.id = sis_events.id_user');

        $query->andFilterWhere(['=', 'sis_events.dominio', isset(Yii::$app->user->identity->dominio) ?
                            Yii::$app->user->identity->dominio : '0'])
                ->andFilterWhere(['=', 'sis_events.status', SisEvents::STATUS_ATIVO]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy(['sis_events.id' => SORT_DESC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['id_user'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sis_events.id' => $this->id,
            'sis_events.status' => $this->status,
            'sis_events.updated_at' => $this->updated_at,
            'sis_events.id_registro' => $this->id_registro,
        ]);

        $query->andFilterWhere(['like', 'sis_events.slug', $this->slug])
                ->andFilterWhere(['like', 'sis_events.dominio', $this->dominio])
                ->andFilterWhere(['like', 'sis_events.evento', $this->evento])
                ->andFilterWhere(['like', 'sis_events.classevento', $this->classevento])
                ->andFilterWhere(['like', 'sis_events.tabela_bd', $this->tabela_bd])
                ->andFilterWhere(['like', 'sis_events.ip', $this->ip])
                ->andFilterWhere(['like', 'sis_events.geo_lt', $this->geo_lt])
                ->andFilterWhere(['like', 'sis_events.geo_ln', $this->geo_ln])
                ->andFilterWhere(['like', 'user.username', $this->id_user]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = strtotime($date_explode[0] . ' 00:00:00');
            $date2 = strtotime($date_explode[1] . ' 23:59:59');
            $query->andFilterWhere(['between', 'sis_events.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
