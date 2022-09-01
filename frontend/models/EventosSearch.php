<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SisEvents;

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
        $query->join('JOIN', \common\models\User::tableName(), 'user.id = eventos.id_user');

        $query->andFilterWhere(['=', 'eventos.dominio', isset(Yii::$app->user->identity->dominio) ?
                            Yii::$app->user->identity->dominio : '0'])
                ->andFilterWhere(['=', 'eventos.status', SisEvents::STATUS_ATIVO]);

        if (!isset(Yii::$app->request->queryParams['sort'])) {
            $query->orderBy(['eventos.id' => SORT_DESC]);
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
            'eventos.id' => $this->id,
            'eventos.status' => $this->status,
            'eventos.updated_at' => $this->updated_at,
            'eventos.id_registro' => $this->id_registro,
        ]);

        $query->andFilterWhere(['like', 'eventos.slug', $this->slug])
                ->andFilterWhere(['like', 'eventos.dominio', $this->dominio])
                ->andFilterWhere(['like', 'eventos.evento', $this->evento])
                ->andFilterWhere(['like', 'eventos.classevento', $this->classevento])
                ->andFilterWhere(['like', 'eventos.tabela_bd', $this->tabela_bd])
                ->andFilterWhere(['like', 'eventos.ip', $this->ip])
                ->andFilterWhere(['like', 'eventos.geo_lt', $this->geo_lt])
                ->andFilterWhere(['like', 'eventos.geo_ln', $this->geo_ln])
                ->andFilterWhere(['like', 'user.username', $this->id_user]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = strtotime($date_explode[0] . ' 00:00:00');
            $date2 = strtotime($date_explode[1] . ' 23:59:59');
            $query->andFilterWhere(['between', 'eventos.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
