<?php

namespace app\models;

use app\models\ItemVenda;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * ItemVendaSearch represents the model behind the search form of `app\models\ItemVenda`.
 */
class ItemVendaSearch extends ItemVenda {

    public $aux_hora;
    public $aux_quantidade;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_venda', 'fk_preco'], 'integer'],
            [['quantidade', 'preco_unitario', 'preco_final'], 'number'],
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
        $query = ItemVenda::find();

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
            'fk_venda' => $this->fk_venda,
            'fk_preco' => $this->fk_preco,
            'quantidade' => $this->quantidade,
            'preco_unitario' => $this->preco_unitario,
            'preco_final' => $this->preco_final,
        ]);

        return $dataProvider;
    }

    public static function searchGrafico($por_hora, $por_dia, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final) {
        $query = ItemVendaSearch::find();
        $groupBy = [];
        $order = [];
        $select = array();

        //  $select[] = 'SUM(item_venda.preco_final) as pagamentos';
        //  $select[] = 'SUM(item_venda.preco_final - preco.quantidade*produto.custo_compra_producao*item_venda.quantidade) as pagamentos_liquido';
        if ($por_hora) {

            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_hora';
            $select[] = 'ROUND(SUM(item_venda.quantidade * preco.quantidade), 2) as aux_quantidade';
            $groupBy[] = 'HOUR(dt_inclusao)';
            $order['HOUR(dt_inclusao)'] = SORT_ASC;
        } elseif ($por_dia) {
            $groupBy[] = 'DAY(dt_venda)';
            $groupBy[] = 'MONTH(dt_venda)';
            $groupBy[] = 'YEAR(dt_venda)';
            $select[] = 'dt_venda';
            $order['dt_venda'] = SORT_ASC;
        } elseif ($por_mes) {
            $groupBy[] = 'MONTH(dt_venda)';
            $groupBy[] = 'YEAR(dt_venda)';
            $select[] = 'DATE_FORMAT(`dt_venda`, "%m/%Y" ) AS  dt_venda';
            $order['dt_venda'] = SORT_ASC;
        } elseif ($por_produto) {
            $groupBy[] = 'fk_produto';
            $select[] = 'produto.nome as produto';
            $select[] = 'SUM(item_venda.quantidade * preco.quantidade) as quantidade';
            $select[] = 'unidade_medida';
            $order['produto.nome'] = SORT_ASC;
        } elseif ($por_cliente) {
            $groupBy[] = 'cliente.nome';
            $select[] = 'cliente.nome as nome_cliente';
            $order['cliente.nome'] = SORT_ASC;
        }



//        if ($apenas_vendas_pagas) {
//            $query->andFilterWhere(['like', 'estado', 'paga']);
//        }


        $query->joinWith(['preco' => function (ActiveQuery $query) {
                $query->joinWith(['produto' => function (ActiveQuery $query) {
                        $query->joinWith('unidadeMedida');
                    }]);
            }]);



        $query->select($select);
        $query->groupBy($groupBy);
        $query->orderBy($order);


        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));

        $query->andWhere("dt_inclusao BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
        //$query->andFilterWhere(['like', 'cliente.nome', $this->nome_cliente]);
        // $query->andFilterWhere(['like', 'produto.nome', $this->produto]);
        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_hora || $por_dia || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
            $query->andWhere('pk_item_venda = -1');
        }

        $query->andWhere(['like', 'unidade_medida', 'Litros']);

        $horas = ['00' => 0, '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0,
            '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0];

        $resultado = ArrayHelper::map($query->all(), 'aux_hora', 'aux_quantidade');

        return array_replace($horas, $resultado);
    }

}
