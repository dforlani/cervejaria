<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Venda;

/**
 * VendaSearch represents the model behind the search form of `app\models\Venda`.
 */
class VendaSearch extends Venda
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_venda', 'fk_cliente', 'fk_comanda'], 'integer'],
            [['fk_usuario_iniciou_venda', 'fk_usuario_recebeu_pagamento', 'estado', 'dt_venda', 'dt_pagamento'], 'safe'],
            [['valor_total', 'desconto', 'valor_final'], 'number'],
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
        $query = Venda::find();

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
            'pk_venda' => $this->pk_venda,
            'fk_cliente' => $this->fk_cliente,
            'fk_comanda' => $this->fk_comanda,
            'valor_total' => $this->valor_total,
            'desconto' => $this->desconto,
            'valor_final' => $this->valor_final,
            'dt_venda' => $this->dt_venda,
            'dt_pagamento' => $this->dt_pagamento,
        ]);

        $query->andFilterWhere(['like', 'fk_usuario_iniciou_venda', $this->fk_usuario_iniciou_venda])
            ->andFilterWhere(['like', 'fk_usuario_recebeu_pagamento', $this->fk_usuario_recebeu_pagamento])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
