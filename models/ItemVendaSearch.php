<?php

namespace app\models;

use app\components\TempoUtil;
use app\models\ItemVenda;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

/**
 * ItemVendaSearch represents the model behind the search form of `app\models\ItemVenda`.
 */
class ItemVendaSearch extends ItemVenda {

    public $aux_temporizador;
    public $aux_quantidade;
    public $aux_nome_produto;
    public $aux_nome_cliente;
    public $aux_dia_semana;
    public $aux_gasto;
    public $pagamentos;
    public $pagamentos_liquido;
    public $quantidade;
    public $unidade_medida;
    public $nome_cliente;
    public $produto;
    public $itens_sem_preco_custo;
    public $pagamentos_bruto;
    public $dt_pagamento;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_venda', 'fk_preco'], 'integer'],
            [['quantidade', 'preco_unitario', 'preco_final'], 'safe'],
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
            'sort' => ['defaultOrder' => [
                    'is_desconto_promocional' => SORT_ASC,
                    'dt_inclusao' => SORT_DESC
                ]]
        ]);

        $this->load($params);


        $query->andFilterWhere([
            'fk_venda' => $this->fk_venda,
            'fk_preco' => $this->fk_preco,
            'quantidade' => $this->quantidade,
            'preco_unitario' => $this->preco_unitario,
            'preco_final' => $this->preco_final,
        ]);

        return $dataProvider;
    }

    public static function searchRelatorio($por_dia, $por_hora, $por_dia_semana, $por_mes_agregado,
            $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial,
            $data_final, $cervejas_selecionadas, $por_forma_venda, $apenas_cervejas) {
        $query = ItemVendaSearch::find();
        $groupBy = [];
        $order = [];
        $select = [];
        $resultado = [];

        $select[] = 'unidade_medida';

        if ($por_hora) {
            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_temporizador';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';

            $order['aux_quantidade'] = SORT_DESC;

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as pagamentos_bruto';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final - preco_custo_item )), 2) as pagamentos_liquido';
            $groupBy[] = 'HOUR(dt_inclusao)';
            $order['HOUR(dt_inclusao)'] = SORT_ASC;
        } elseif ($por_dia) {
            $select[] = 'DATE_FORMAT(dt_inclusao, "%d/%m/%Y") as aux_temporizador';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as pagamentos_bruto';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final - preco_custo_item )), 2) as pagamentos_liquido';


            $groupBy[] = 'aux_temporizador';
            $order['dt_inclusao'] = SORT_ASC;
        } elseif ($por_dia_semana) {

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as pagamentos_bruto';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final - preco_custo_item )), 2) as pagamentos_liquido';

            $select[] = 'WEEKDAY(dt_inclusao) as aux_temporizador';
            $groupBy[] = 'WEEKDAY(dt_inclusao)';
            $order['WEEKDAY(dt_inclusao)'] = SORT_ASC;
        } elseif ($por_mes_agregado) {

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as pagamentos_bruto';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final - preco_custo_item )), 2) as pagamentos_liquido';


            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m" ) AS  aux_temporizador';
            $order['MONTH(dt_inclusao)'] = SORT_ASC;
            $groupBy[] = 'MONTH(dt_inclusao)';
        } elseif ($por_mes) {

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as pagamentos_bruto';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final - preco_custo_item )), 2) as pagamentos_liquido';


            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m/%y" ) AS  aux_temporizador';
            $order['dt_inclusao'] = SORT_ASC;
            $groupBy[] = 'YEAR(dt_inclusao)';
            $groupBy[] = 'MONTH(dt_inclusao)';
        }else{
              $order['dt_inclusao'] = SORT_ASC;
        }
        
        if ($por_forma_venda) {
            $groupBy[] = 'fk_produto';
            $groupBy[] = 'pk_preco';
            $select[] = 'CONCAT(produto.nome," - " ,preco.denominacao) as aux_nome_produto';
        } else
        if ($por_produto) {
            $groupBy[] = 'fk_produto';
            $select[] = 'produto.nome as aux_nome_produto';
        } elseif ($por_cliente) {
            $select[] = 'cliente.nome as aux_nome_cliente';
            $groupBy[] = 'aux_nome_cliente';
            $order['aux_nome_cliente'] = SORT_ASC;
        }



        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));


        $query->select($select);

        $query->andWhere("dt_inclusao BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
        //$query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);
        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_hora || $por_dia_semana || $por_dia || $por_mes_agregado || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
            $query->andWhere('pk_item_venda = -1');
        }

        //adiciona as cervejas selecionadas, se for pra filtras
        if (!empty($cervejas_selecionadas)) {
            $query->andWhere(['IN', 'fk_produto', $cervejas_selecionadas]);
        }

        $query->groupBy($groupBy);
        $query->orderBy($order);
        $query->joinWith(['preco' => function (ActiveQuery $query) {
                $query->joinWith(['produto' => function (ActiveQuery $query) {
                        $query->joinWith('unidadeMedida');
                    }]);
            }, 'venda', 'venda.cliente']);

        if ($apenas_cervejas) {
            $query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
        ]);


        return $dataProvider;
    }

//    public static function searchRelatorio($por_dia, $por_hora, $por_dia_semana, $por_mes_agregado,
//            $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial,
//            $data_final, $por_gasto, $por_litro, $cervejas_selecionadas, $por_forma_venda) {
//        $query = ItemVendaSearch::find();
//        $groupBy = [];
//        $order = [];
//        $select = [];
//        $resultado = [];
//
//
//        $select[] = 'SUM(item_venda.preco_final) as pagamentos';
//        $select[] = 'SUM(item_venda.preco_final - item_venda.preco_custo_item) as pagamentos_liquido';
//        $select[] = 'SUM(if(is_desconto_promocional = FALSE AND item_venda.preco_custo_item = 0,1,0)) as itens_sem_preco_custo';
//
//        if ($por_hora) {
//            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_temporizador';
//            if ($por_litro) {
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
//                $order['aux_quantidade'] = SORT_DESC;
//            }
//            if ($por_gasto)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
//            $groupBy[] = 'HOUR(dt_inclusao)';
//            $order['HOUR(dt_inclusao)'] = SORT_ASC;
//        } elseif ($por_dia) {
//            $select[] = 'DATE_FORMAT(dt_inclusao, "%d/%m") as aux_temporizador';
//            $select[] = 'dt_pagamento';
//
//            if ($por_litro)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
//            if ($por_gasto)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
//
//
//            $groupBy[] = 'dt_pagamento';
//            $order['dt_pagamento'] = SORT_ASC;
//        } elseif ($por_dia_semana) {
//            if ($por_litro)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
//            if ($por_gasto)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
//
//            $select[] = 'WEEKDAY(dt_inclusao) as aux_temporizador';
//            $groupBy[] = 'WEEKDAY(dt_inclusao)';
//            $order['WEEKDAY(dt_inclusao)'] = SORT_ASC;
//        } elseif ($por_mes_agregado) {
//            if ($por_litro)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
//            if ($por_gasto)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
//
//
//            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m" ) AS  aux_temporizador';
//            $order['MONTH(dt_inclusao)'] = SORT_ASC;
//            $groupBy[] = 'MONTH(dt_inclusao)';
//        } elseif ($por_mes) {
//            if ($por_litro)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
//            if ($por_gasto)
//                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
//
//            $select[] = 'DATE_FORMAT(`dt_pagamento`, "%m/%Y" ) AS  dt_pagamento';
//            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m/%y" ) AS  aux_temporizador';
//            $order['dt_inclusao'] = SORT_ASC;
//            $groupBy[] = 'YEAR(dt_inclusao)';
//            $groupBy[] = 'MONTH(dt_inclusao)';
//        }
//        if ($por_forma_venda) {
//            $groupBy[] = 'fk_produto';
//            $groupBy[] = 'pk_preco';
//            $select[] = 'CONCAT(produto.nome," - " ,preco.denominacao) as aux_nome_produto';
//        } else
//        if ($por_produto) {
//            $groupBy[] = 'fk_produto';
//            $select[] = 'produto.nome as aux_nome_produto';
//            $select[] = 'SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)) as aux_quantidade';
//            $select[] = 'unidade_medida';
//            $order['aux_nome_produto'] = SORT_ASC;
//        } elseif ($por_cliente) {
//            $select[] = 'cliente.nome as aux_nome_cliente';
//            $groupBy[] = 'aux_nome_cliente';
//            $order['aux_nome_cliente'] = SORT_ASC;
//        }
//
//
//
//        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
//        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));
//
//
//        $query->select($select);
//
//        $query->andWhere("dt_inclusao BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
//        $query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);
//
//        //precisa ter ao menos algo selecionado para que a consulta seja feita
//        if (!($por_hora || $por_dia_semana || $por_dia || $por_mes_agregado || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
//            $query->andWhere('pk_item_venda = -1');
//        }
//
//        //adiciona as cervejas selecionadas, se for pra filtras
//        if (!empty($cervejas_selecionadas)) {
//            $query->andWhere(['IN', 'pk_produto', $cervejas_selecionadas]);
//        }
//
//        $query->groupBy($groupBy);
//        $query->orderBy($order);
//        $query->joinWith(['preco' => function (ActiveQuery $query) {
//                $query->joinWith(['produto' => function (ActiveQuery $query) {
//                        $query->joinWith('unidadeMedida');
//                    }]);
//            }, 'venda', 'venda.cliente']);
//            
////            echo $query->createCommand()->rawSql;
//
//
//        $dataProvider = new ArrayDataProvider([
//            'allModels' => $query->all(),
//        ]);
//
//
//        return $dataProvider;
//    }

    public static function searchGrafico($por_dia, $por_hora, $por_dia_semana, $por_mes_agregado,
            $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial,
            $data_final, $por_gasto, $por_litro, $cervejas_selecionadas, $por_forma_venda, $apenas_cervejas_ativas) {
        $query = ItemVendaSearch::find();
        $groupBy = [];
        $order = [];
        $select = [];
        $resultado = [];

        if ($por_hora) {
            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_temporizador';
            if ($por_litro) {
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
                $order['aux_quantidade'] = SORT_DESC;
            }
            if ($por_gasto)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';
            $groupBy[] = 'HOUR(dt_inclusao)';
            $order['HOUR(dt_inclusao)'] = SORT_ASC;
        } elseif ($por_dia) {
            $select[] = 'DATE_FORMAT(dt_inclusao, "%d/%m") as aux_temporizador';

            if ($por_litro)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            if ($por_gasto)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';


            $groupBy[] = 'aux_temporizador';
            $order['aux_temporizador'] = SORT_ASC;
        } elseif ($por_dia_semana) {
            if ($por_litro)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            if ($por_gasto)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';

            $select[] = 'WEEKDAY(dt_inclusao) as aux_temporizador';
            $groupBy[] = 'WEEKDAY(dt_inclusao)';
            $order['WEEKDAY(dt_inclusao)'] = SORT_ASC;
        } elseif ($por_mes_agregado) {
            if ($por_litro)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            if ($por_gasto)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';


            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m" ) AS  aux_temporizador';
            $order['MONTH(dt_inclusao)'] = SORT_ASC;
            $groupBy[] = 'MONTH(dt_inclusao)';
        } elseif ($por_mes) {
            if ($por_litro)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            if ($por_gasto)
                $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.preco_final)), 2) as aux_gasto';


            $select[] = 'DATE_FORMAT(`dt_inclusao`, "%m/%y" ) AS  aux_temporizador';
            $order['dt_inclusao'] = SORT_ASC;
            $groupBy[] = 'YEAR(dt_inclusao)';
            $groupBy[] = 'MONTH(dt_inclusao)';
        }
        if ($por_forma_venda) {
            $groupBy[] = 'fk_produto';
            $groupBy[] = 'pk_preco';
            $select[] = 'CONCAT(produto.nome," - " ,preco.denominacao) as aux_nome_produto';
            $order['aux_nome_produto'] = SORT_ASC;
        } else
        if ($por_produto) {
            $groupBy[] = 'fk_produto';
            $select[] = 'produto.nome as aux_nome_produto';
        } elseif ($por_cliente) {
            $select[] = 'cliente.nome as aux_nome_cliente';
            $groupBy[] = 'aux_nome_cliente';
            $order['aux_nome_cliente'] = SORT_ASC;
        }



        $data_inicial_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_inicial)));
        $data_final_convertida = date("Y-m-d", strtotime(str_replace('/', '-', $data_final)));


        $query->select($select);

        $query->andWhere("dt_inclusao BETWEEN  '$data_inicial_convertida' AND '$data_final_convertida 23:59:59.999'");
        //$query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);
        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_hora || $por_dia_semana || $por_dia || $por_mes_agregado || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
            $query->andWhere('pk_item_venda = -1');
        }

        //adiciona as cervejas selecionadas, se for pra filtras
        if (!empty($cervejas_selecionadas)) {
            $query->andWhere(['IN', 'pk_produto', $cervejas_selecionadas]);
        }

        if ($apenas_cervejas_ativas) {
            $query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);
        }

        $query->groupBy($groupBy);
        $query->orderBy($order);
        $query->joinWith(['preco' => function (ActiveQuery $query) {
                $query->joinWith(['produto' => function (ActiveQuery $query) {
                        $query->joinWith('unidadeMedida');
                    }]);
            }, 'venda', 'venda.cliente']);


        //monta o array de resultado por produto, por cliente ou total  
        if ($por_produto || $por_forma_venda) {
            $result = $query->all();
            foreach ($result as $item) {
                if ($por_litro)
                    $resultado[$item->aux_nome_produto . ' L'][$item->aux_temporizador] = $item->aux_quantidade;
                if ($por_gasto)
                    $resultado[$item->aux_nome_produto . ' R$'][$item->aux_temporizador] = $item->aux_gasto;
            }
        } elseif ($por_cliente) {
            $result = $query->all();
            foreach ($result as $item) {
                if ($por_litro)
                    $resultado[!empty($item->aux_nome_cliente) ? $item->aux_nome_cliente . " L" : "Sem Identificação L"][$item->aux_temporizador] = $item->aux_quantidade;
                if ($por_gasto)
                    $resultado[!empty($item->aux_nome_cliente) ? $item->aux_nome_cliente . " R$" : "Sem Identificação R$"][$item->aux_temporizador] = $item->aux_gasto;
            }
        }

        //ordena em ordem alfabética
        ksort($resultado);

        //faz o merge do array resultado com arrays que possuem todos os dias, horas e etc, para completar lacunas de tempo
        if ($por_hora) {
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(TempoUtil::getHoras(), $agrupamentos);
            }
        } elseif ($por_dia) {
            $dias_no_periodo = TempoUtil::getDiasNoPeriodo($data_inicial_convertida, $data_final_convertida);
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace($dias_no_periodo, $agrupamentos);
            }
        } elseif ($por_dia_semana) {
            $resultado = TempoUtil::convertWeekDayMySQLtoDiasSemana($resultado);
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(TempoUtil::getDiasSemana(), $agrupamentos);
            }
        } elseif ($por_mes_agregado) {
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(TempoUtil::getMeseDoAno(), $agrupamentos);
            }
        } elseif ($por_mes) {

            foreach ($resultado as $index => $agrupamento) {

                $resultado[$index] = array_replace(TempoUtil::getMeseDoAnoNoPeriodo($data_inicial_convertida, $data_final_convertida), $agrupamento);
            }
        }

        self::removeExtremidades($resultado);

        return $resultado;
    }

    public static function removeExtremidades(array &$resultado) {
        //busca extremidades pra não remover
        $extremidades = [];
        foreach ($resultado as $index => $agrupamentos) {
            foreach ($agrupamentos as $tempo => $valor) {
                if (!empty($valor))
                    $extremidades[$tempo] = $valor;
            }
        }

        //remove as extremidades não utilizadas
        foreach ($resultado as $index => &$agrupamentos) {
            //remove os itens vazios da esquerda, até encontrar algum item com valor
            foreach ($agrupamentos as $tempo => $valor) {
                if (empty($valor) && !isset($extremidades[$tempo])) {
                    unset($agrupamentos[$tempo]);
                } else {
                    break;
                }
            }

            //remove os itens vazios da direita, até encontrar algum item com valor
            $agrupamento_reverse = array_reverse($agrupamentos, true);
            foreach ($agrupamento_reverse as $tempo => $valor) {
                if (empty($valor)) {
                    unset($agrupamentos[$tempo]);
                } else {
                    break;
                }
            }
        }
    }

}
