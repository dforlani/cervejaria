<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Comanda;

/**
 * ComandaSearch represents the model behind the search form of `app\models\Comanda`.
 */
class ComandaSearch extends Comanda
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_comanda', 'numero'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Comanda::find();

        // add conditions that should always apply here

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
            'pk_comanda' => $this->pk_comanda,
            'numero' => $this->numero,
        ]);

        return $dataProvider;
    }
}
