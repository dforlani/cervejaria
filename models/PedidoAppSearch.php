<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PedidoApp;

/**
 * EntradaSearch represents the model behind the search form of `app\models\Entrada`.
 */
class PedidoAppSearch extends PedidoApp {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['status'], 'safe'],
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
        $query = PedidoApp::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'pagination' => false,
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
            'status' => $this->status,
        ]);

        //$query->andFilterWhere(['like', 'fk_usuario', $this->fk_usuario]);

        return $dataProvider;
    }

}
