<?php

namespace app\models;

use app\models\Venda;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * VendaSearch represents the model behind the search form of `app\models\Venda`.
 */
class VendaSearch extends Venda {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_venda', 'fk_cliente', 'fk_comanda'], 'integer'],
            [['fk_usuario_iniciou_venda', 'fk_usuario_recebeu_pagamento', 'estado', 'dt_venda', 'dt_pagamento'], 'safe'],
            [['valor_total', 'desconto', 'valor_final'], 'number'],
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
        $query = Venda::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['estado' => SORT_ASC]]
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

    public function searchRelatorio($por_dia, $por_mes, $por_produto, $apenas_vendas_pagas) {
        $query = Venda::find();
        $groupBy = [];
        $select = [];

        if ($por_dia) {
            $groupBy[] = 'DAY(dt_venda)';
            $groupBy[] = 'MONTH(dt_venda)';
            $groupBy[] = 'YEAR(dt_venda)';
            $select[] = 'dt_venda';
            $select[] = 'SUM(valor_final) as pagamentos';
        } elseif ($por_mes) {
            $groupBy[] = 'MONTH(dt_venda)';
            $groupBy[] = 'YEAR(dt_venda)';
            $select[] = 'DATE_FORMAT(`dt_venda`, "%m/%Y" ) AS  dt_venda';
            $select[] = 'SUM(valor_final) as pagamentos';
        }
        $query->joinWith(['itensVenda' => function (\yii\db\ActiveQuery $query) {
                $query->joinWith(['preco' => function (\yii\db\ActiveQuery $query) {
                        $query->joinWith(['produto' => function (\yii\db\ActiveQuery $query) {
                                $query->joinWith('unidadeMedida');
                            }]);
                    }]);
            }]);
        if ($por_produto) {
            $groupBy[] = 'fk_produto';

            $select[] = 'nome';
            $select[] = 'SUM(item_venda.quantidade * preco.quantidade) as quantidade';

            $select[] = 'unidade_medida';
        }
        $select[] = 'SUM(item_venda.preco_final) as pagamentos';
        $select[] = 'SUM(item_venda.preco_final - preco.quantidade*produto.custo_compra_producao*item_venda.quantidade) as pagamentos_liquido';

        if ($apenas_vendas_pagas) {
            $query->andFilterWhere(['like', 'estado', 'paga']);
        }

        $query->select($select);
        $query->groupBy($groupBy);
        $query->orderBy('dt_venda');

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
        ]);


        return $dataProvider;
    }

}
