<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearchNewUser extends User {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['hash'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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

        $query
                ->andFilterWhere(['or',
                    ['=', 'status', User::STATUS_NEW_USER],
                    ['=', 'status', User::STATUS_NEW_USER_UNREGISTERED],
                    ['=', 'status', User::STATUS_NEW_USER_RS],
        ]);

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

        $query->andFilterWhere([
            'hash' => $this->hash,
        ]);

        return $dataProvider;
    }

}
