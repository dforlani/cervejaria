<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entrada;

/**
 * EntradaSearch represents the model behind the search form of `app\models\Entrada`.
 */
class EntradaSearch extends Entrada
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_entrada', 'fk_produto'], 'integer'],
            [['fk_usuario', 'dt_entrada'], 'safe'],
            [['quantidade', 'custo'], 'number'],
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
        $query = Entrada::find();

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
            'pk_entrada' => $this->pk_entrada,
            'fk_produto' => $this->fk_produto,
            'quantidade' => $this->quantidade,
            'dt_entrada' => $this->dt_entrada,
            'custo' => $this->custo,
        ]);

        $query->andFilterWhere(['like', 'fk_usuario', $this->fk_usuario]);

        return $dataProvider;
    }
}
