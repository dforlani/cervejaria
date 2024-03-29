<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cliente;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class ClienteSearch extends Cliente {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_cliente'], 'integer'],
            [['nome', 'telefone', 'cpf', 'dt_nascimento'], 'safe'],
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
        $query = Cliente::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['nome' => SORT_ASC]],
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'pk_cliente' => $this->pk_cliente,
            'dt_nascimento' => $this->dt_nascimento,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
                ->andFilterWhere(['like', 'telefone', $this->telefone])
                ->andFilterWhere(['like', 'cpf', $this->cpf]);

        return $dataProvider;
    }

}
