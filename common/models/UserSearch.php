<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'administrador', 'gestor', 'usuarios', 'cadastros', 'folha', 'financeiro', 'parametros'], 'integer'],
            [['username', 'hash', 'auth_key', 'password_hash', 'password_reset_token', 'github', 'email', 'tel_contato', 'slug', 'base_servico', 'cliente', 'dominio'], 'safe'],
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
        $query = User::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->identity->administrador) {
            $query->andFilterWhere(['=', 'dominio', isset(Yii::$app->user->identity->dominio) ?
                                Yii::$app->user->identity->dominio : '0'])
                    ->andFilterWhere(['=', 'cliente', isset(Yii::$app->user->identity->cliente) ?
                                Yii::$app->user->identity->cliente : '0'])
                    ->andFilterWhere(['=', 'status', User::STATUS_ATIVO]);
        }

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
            'administrador' => $this->administrador,
            'gestor' => $this->gestor,
            'usuarios' => $this->usuarios,
            'cadastros' => $this->cadastros,
            'folha' => $this->folha,
            'financeiro' => $this->financeiro,
            'parametros' => $this->parametros,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'hash', $this->hash])
                ->andFilterWhere(['like', 'auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'github', $this->github])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'tel_contato', $this->tel_contato])
                ->andFilterWhere(['like', 'slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.base_servico', $this->base_servico])
                ->andFilterWhere(['like', $this::tableName() . '.cliente', $this->cliente])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio]);

//         filtrar por período
        if (isset($this->created_at) && $this->created_at != '') {
            $date_explode = explode(" - ", $this->created_at);
            $date1 = trim(strtotime($date_explode[0] . ' 00:00:00'));
            $date2 = trim(strtotime($date_explode[1] . ' 23:59:59'));
            $query->andFilterWhere(['between', 'user.created_at', $date1, $date2]);
        }

        return $dataProvider;
    }

}
