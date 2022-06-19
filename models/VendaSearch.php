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

    public $itens_sem_preco_custo;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_venda', 'fk_cliente', 'fk_comanda'], 'integer'],
            [['fk_usuario_iniciou_venda', 'fk_usuario_recebeu_pagamento', 'aux_nome_cliente', 'estado', 'dt_venda', 'dt_pagamento', 'cliente', 'produto',], 'safe'],
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
          //  'pagination' => false,
            'query' => $query,
            'sort' => ['defaultOrder' => ['dt_venda' => SORT_DESC, 'estado' => SORT_ASC]]
        ]);

        $this->load($params);

        //REMOVIDO POR QUE DÁ PROBLEMA AO USAR JUNTO DO BEFOREVALIDATE, JÁ QUE O VALIDATE ATRIBUI VALORES PARA ALGUMAS VARIÁVEIS
        //if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        // return $dataProvider;
        // }
        $query->select[] = "concat(cliente.nome, ' ', nome_temp) as aux_nome_cliente";
        $query->select[] = "venda.*";
        $query->joinWith(['cliente']);

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
                ->andFilterWhere(['like', 'cliente.nome', $this->aux_nome_cliente])
                ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }

    public function searchRelatorio($por_dia, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final) {
        $query = VendaSearch::find();
        $groupBy = [];
        $order = [];

        $select[] = 'SUM(item_venda.preco_final) as pagamentos';
        $select[] = 'SUM(item_venda.preco_final - item_venda.preco_custo_item) as pagamentos_liquido';
        $select[] = 'SUM(if(is_desconto_promocional = FALSE AND item_venda.preco_custo_item = 0,1,0)) as itens_sem_preco_custo';

        if ($por_dia) {
            $groupBy[] = 'DAY(dt_pagamento)';
            $groupBy[] = 'MONTH(dt_pagamento)';
            $groupBy[] = 'YEAR(dt_pagamento)';
            $select[] = 'dt_pagamento';
            $order['dt_pagamento'] = SORT_ASC;
        } elseif ($por_mes) {
            $groupBy[] = 'MONTH(dt_pagamento)';
            $groupBy[] = 'YEAR(dt_pagamento)';
            $select[] = 'DATE_FORMAT(`dt_pagamento`, "%m/%Y" ) AS  dt_pagamento';
            $order['YEAR(dt_pagamento)'] = SORT_ASC;
            $order['MONTH(dt_pagamento)'] = SORT_ASC;
        }

        if ($por_produto) {
            $groupBy[] = 'fk_produto';
            $select[] = 'produto.nome as produto';
            $select[] = 'SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)) as quantidade';
            $select[] = 'unidade_medida';
            $order['produto.nome'] = SORT_ASC;
        }

        if ($por_cliente) {
            $groupBy[] = 'cliente.nome';
            $select[] = 'cliente.nome as nome_cliente';
            $order['cliente.nome'] = SORT_ASC;
        }



        if ($apenas_vendas_pagas) {
            $query->andFilterWhere(['like', 'estado', 'paga']);
        }

        $query->joinWith(['cliente', 'itensVenda' => function (\yii\db\ActiveQuery $query) {
                $query->joinWith(['preco' => function (\yii\db\ActiveQuery $query) {
                        $query->joinWith(['produto' => function (\yii\db\ActiveQuery $query) {
                                $query->joinWith('unidadeMedida');
                            }]);
                    }]);
            }]);


        $query->select($select);
        $query->groupBy($groupBy);
        $query->orderBy($order);


        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));

        $query->andWhere("dt_pagamento BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
        $query->andFilterWhere(['like', 'cliente.nome', $this->nome_cliente]);
        $query->andFilterWhere(['like', 'produto.nome', $this->produto]);

        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_dia || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
            $query->andWhere('pk_venda = -1');
        }


        $dataProvider = new ArrayDataProvider([
            'pagination' => false,
            'allModels' => $query->all(),
        ]);


        return $dataProvider;
    }

}
