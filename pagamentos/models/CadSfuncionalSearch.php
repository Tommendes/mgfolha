<?php

namespace pagamentos\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use pagamentos\models\CadSfuncional;

/**
 * CadSfuncionalSearch represents the model behind the search form of `pagamentos\models\CadSfuncional`.
 */
class CadSfuncionalSearch extends CadSfuncional {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'status', 'evento', 'created_at', 'updated_at', 'id_cad_servidores', 'id_local_trabalho', 'id_cad_principal', 'rais', 'dirf', 'sefip', 'sicap', 'insalubridade', 'decimo', 'id_cat_sefip'], 'integer'],
            [['slug', 'dominio', 'ano', 'mes', 'parcela', 'id_escolaridade', 'escolaridaderais', 'id_vinculo', 'ocorrencia', 'molestia', 'd_laudomolestia', 'manad_tiponomeacao', 'manad_numeronomeacao', 'd_tempo', 'd_tempofim', 'd_beneficio'], 'safe'],
            [['carga_horaria', 'n_valorbaseinss'], 'number'],
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
        $query = CadSfuncional::find();
        $mes = Yii::$app->user->identity->per_mes;
        $ano = Yii::$app->user->identity->per_ano;
        $tableName = $this::tableName();

        // add conditions that should always apply here

        $query->join('join', CadServidores::tableName(), CadServidores::tableName() . '.id = ' . self::tableName() . '.id_cad_servidores');
        $query->where(['id_cad_servidores' => $id_cad_servidores]);
        $query->andWhere([
            $tableName . '.dominio' => Yii::$app->user->identity->dominio,
            $tableName . '.parcela' => Yii::$app->user->identity->per_parcela,
        ]);
        $query->andWhere("LAST_DAY(CONCAT($tableName.ano, '/', $tableName.mes, '/01')) <= LAST_DAY('$ano/$mes/01')");

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
            $this::tableName() . '.id_local_trabalho' => $this->id_local_trabalho,
            $this::tableName() . '.id_cad_principal' => $this->id_cad_principal,
            $this::tableName() . '.rais' => $this->rais,
            $this::tableName() . '.dirf' => $this->dirf,
            $this::tableName() . '.sefip' => $this->sefip,
            $this::tableName() . '.sicap' => $this->sicap,
            $this::tableName() . '.insalubridade' => $this->insalubridade,
            $this::tableName() . '.decimo' => $this->decimo,
            $this::tableName() . '.id_cat_sefip' => $this->id_cat_sefip,
            $this::tableName() . '.carga_horaria' => $this->carga_horaria,
            $this::tableName() . '.n_valorbaseinss' => $this->n_valorbaseinss,
        ]);

        $query->andFilterWhere(['like', $this::tableName() . '.slug', $this->slug])
                ->andFilterWhere(['like', $this::tableName() . '.dominio', $this->dominio])
                ->andFilterWhere(['like', $this::tableName() . '.ano', $this->ano])
                ->andFilterWhere(['like', $this::tableName() . '.mes', $this->mes])
                ->andFilterWhere(['like', $this::tableName() . '.parcela', $this->parcela])
                ->andFilterWhere(['like', $this::tableName() . '.id_escolaridade', $this->id_escolaridade])
                ->andFilterWhere(['like', $this::tableName() . '.escolaridaderais', $this->escolaridaderais])
                ->andFilterWhere(['like', $this::tableName() . '.id_vinculo', $this->id_vinculo])
                ->andFilterWhere(['like', $this::tableName() . '.ocorrencia', $this->ocorrencia])
                ->andFilterWhere(['like', $this::tableName() . '.molestia', $this->molestia])
                ->andFilterWhere(['like', $this::tableName() . '.d_laudomolestia', $this->d_laudomolestia])
                ->andFilterWhere(['like', $this::tableName() . '.manad_tiponomeacao', $this->manad_tiponomeacao])
                ->andFilterWhere(['like', $this::tableName() . '.manad_numeronomeacao', $this->manad_numeronomeacao])
                ->andFilterWhere(['like', $this::tableName() . '.d_tempo', $this->d_tempo])
                ->andFilterWhere(['like', $this::tableName() . '.d_tempofim', $this->d_tempofim])
                ->andFilterWhere(['like', $this::tableName() . '.d_beneficio', $this->d_beneficio]);

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
