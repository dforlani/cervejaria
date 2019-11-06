<?php

namespace app\models;

use app\models\ItemVenda;
use DateInterval;
use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * ItemVendaSearch represents the model behind the search form of `app\models\ItemVenda`.
 */
class ItemVendaSearch extends ItemVenda {

    public $aux_temporizador;
    public $aux_quantidade;
    public $aux_nome_produto;
    public $aux_nome_cliente;
    public $aux_dia_semana;

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

    public static function searchGrafico($por_dia, $por_hora, $por_dia_semana, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final) {
        $query = ItemVendaSearch::find();
        $groupBy = [];
        $order = [];
        $select = [];
        $resultado = [];

        if ($por_hora) {
            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_temporizador';
            $select[] = 'ROUND(SUM(item_venda.quantidade * preco.quantidade), 2) as aux_quantidade';
            $groupBy[] = 'HOUR(dt_inclusao)';
            $order['HOUR(dt_inclusao)'] = SORT_ASC;
            $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_dia) {
            $select[] = 'DATE_FORMAT(dt_inclusao, "%d/%m") as aux_temporizador';
            $select[] = 'ROUND(SUM(item_venda.quantidade * preco.quantidade), 2) as aux_quantidade';
            $groupBy[] = 'aux_temporizador';
            $order['aux_temporizador'] = SORT_ASC;
            // $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_dia_semana) {
            $select[] = 'WEEKDAY(dt_inclusao) as aux_temporizador';
            $select[] = 'ROUND(SUM(item_venda.quantidade * preco.quantidade), 2) as aux_quantidade';
            $groupBy[] = 'WEEKDAY(dt_inclusao)';
            $order['WEEKDAY(dt_inclusao)'] = SORT_ASC;
            // $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_mes) {

            $select[] = 'ROUND(SUM(item_venda.quantidade * preco.quantidade), 2) as aux_quantidade';
            $select[] = 'DATE_FORMAT(`dt_venda`, "%m" ) AS  aux_temporizador';
            $order['MONTH(dt_venda)'] = SORT_ASC;
            $groupBy[] = 'MONTH(dt_venda)';
            // $order['aux_quantidade'] = SORT_DESC;
        }

        if ($por_produto) {
            $groupBy[] = 'fk_produto';
            $select[] = 'produto.nome as aux_nome_produto';
            // $order['produto.nome'] = SORT_ASC;
        } elseif ($por_cliente) {
            $select[] = 'cliente.nome as aux_nome_cliente';
            $groupBy[] = 'aux_nome_cliente';
            $order['aux_nome_cliente'] = SORT_ASC;
        }

        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));

        $query->select($select);

        $query->andWhere("dt_inclusao BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
        $query->andWhere(['like', 'unidade_medida', 'Litros']);

        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_hora || $por_dia_semana || $por_dia || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
            $query->andWhere('pk_item_venda = -1');
        }

        $query->groupBy($groupBy);
        $query->orderBy($order);
        $query->joinWith(['preco' => function (ActiveQuery $query) {
                $query->joinWith(['produto' => function (ActiveQuery $query) {
                        $query->joinWith('unidadeMedida');
                    }]);
            }, 'venda', 'venda.cliente']);


        //monta o array de resultado por produto, por cliente ou total  
        if ($por_produto) {
            foreach ($query->all() as $item) {
                $resultado[$item->aux_nome_produto][$item->aux_temporizador] = $item->aux_quantidade;
            }
        } elseif ($por_cliente) {
            foreach ($query->all() as $item) {
                $resultado[$item->aux_nome_cliente][$item->aux_temporizador] = $item->aux_quantidade;
            }
        } else {
            $resultado['total'] = ArrayHelper::map($query->all(), 'aux_temporizador', 'aux_quantidade');
        }


        if ($por_hora) {
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(ItemVendaSearch::getHoras(), $agrupamentos);
            }
        }elseif ($por_dia) {
            $dias_no_periodo = ItemVendaSearch::getDiasNoPeriodo($data_inicial_convertida, $data_final_convertida);
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace($dias_no_periodo, $agrupamentos);
            }
        } 
        elseif ($por_dia_semana) {
            $resultado = ItemVendaSearch::convertWeekDayMySQLtoDiasSemana($resultado);
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(ItemVendaSearch::getDiasSemana(), $agrupamentos);
            }
        } elseif ($por_mes) {
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(ItemVendaSearch::getMeseDoAno(), $agrupamentos);
            }
        }

        return $resultado;
    }

    public static function getHoras() {
        return ['08' => 0, '09' => 0, '10' => 0,
            '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '00' => 0, '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0];
    }

    public static function getDiasSemana() {
        return ['Seg' => 0, "Ter" => 0, "Qua" => 0, 'Qui' => 0, "Sex" => 0, "Sab" => 0, "Dom" => 0];
    }

    public static function getMeseDoAno() {
        return ['01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0,];
    }

    public static function convertWeekDayMySQLtoDiasSemana($lista) {
        $dePara = [0 => 'Seg', 1 => "Ter", 2 => "Qua", 3 => 'Qui', 4 => "Sex", 5 => "Sab", 6 => "Dom"];
        $resultado = [];
        foreach ($lista as $keyAGrup => $agrupador) {
            $resultado[$keyAGrup] = [];
            foreach ($agrupador as $dayWeek => $item) {
                $resultado[$keyAGrup][$dePara[$dayWeek]] = $item;
            }
        }
        return $resultado;
    }

    public static function getDiasNoPeriodo($inicio, $fim) {
        $resultado = [];
        $d_inicio = new DateTime($inicio);
        $d_fim = new DateTime($fim);
        $resultado[$d_inicio->format('d/m')] = 0;

        $intervalo = new DateInterval('P1D');
        while ($d_inicio < $d_fim) {
            $d_inicio->add($intervalo);
            $resultado[$d_inicio->format('d/m')] = 0;
        }


        return $resultado;
    }

}
