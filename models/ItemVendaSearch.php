<?php

namespace app\models;

use app\components\TempoUtil;
use app\models\ItemVenda;
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
             'sort'=> ['defaultOrder' => [
                 'is_desconto_promocional'=>SORT_ASC, 
                 'dt_inclusao'=>SORT_DESC
                 ]]
        ]);

        $this->load($params);


        //RETIRADO PQ DÁ CONFLITO COM O BEFOREVALIDATE, JÁ QUE O BEFORE ADICIONA UM VALOR E ISSO INFLUI NO FILTRO
        //if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        //  return $dataProvider;
        // }
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

    public static function searchGrafico($por_dia, $por_hora, $por_dia_semana, $por_mes_agregado, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final) {
        $query = ItemVendaSearch::find();
        $groupBy = [];
        $order = [];
        $select = [];
        $resultado = [];

        if ($por_hora) {
            $select[] = 'DATE_FORMAT(dt_inclusao,"%H") as aux_temporizador';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            $groupBy[] = 'HOUR(dt_inclusao)';
            $order['HOUR(dt_inclusao)'] = SORT_ASC;
            $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_dia) {
            $select[] = 'DATE_FORMAT(dt_inclusao, "%d/%m") as aux_temporizador';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            $groupBy[] = 'aux_temporizador';
            $order['aux_temporizador'] = SORT_ASC;
            // $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_dia_semana) {
            $select[] = 'WEEKDAY(dt_inclusao) as aux_temporizador';
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            $groupBy[] = 'WEEKDAY(dt_inclusao)';
            $order['WEEKDAY(dt_inclusao)'] = SORT_ASC;
            // $order['aux_quantidade'] = SORT_DESC;
        } elseif ($por_mes_agregado) {

            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            $select[] = 'DATE_FORMAT(`dt_venda`, "%m" ) AS  aux_temporizador';
            $order['MONTH(dt_venda)'] = SORT_ASC;  
            $groupBy[] = 'MONTH(dt_venda)';            
        } elseif ($por_mes) {
            $select[] = 'ROUND(SUM(IF(is_desconto_promocional, 0, item_venda.quantidade * preco.quantidade)), 2) as aux_quantidade';
            $select[] = 'DATE_FORMAT(`dt_venda`, "%m/%y" ) AS  aux_temporizador';
            $order['MONTH(dt_venda)'] = SORT_ASC;
            $order['YEAR(dt_venda)'] = SORT_ASC;
            $groupBy[] = 'YEAR(dt_venda)';
            $groupBy[] = 'MONTH(dt_venda)';            
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
        $query->andWhere(['like', 'tipo_produto', Produto::$TIPO_CERVEJA]);

        //precisa ter ao menos algo selecionado para que a consulta seja feita
        if (!($por_hora || $por_dia_semana || $por_dia || $por_mes_agregado || $por_mes || $por_produto || $apenas_vendas_pagas || $por_cliente)) {
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
                $resultado[!empty($item->aux_nome_cliente) ? $item->aux_nome_cliente:"Sem Identificação" ][$item->aux_temporizador] = $item->aux_quantidade;
            }
        } else {
            $resultado['total'] = ArrayHelper::map($query->all(), 'aux_temporizador', 'aux_quantidade');
        }


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
        }elseif ($por_mes) {
            foreach ($resultado as $index => $agrupamentos) {
                $resultado[$index] = array_replace(TempoUtil::getMeseDoAnoNoPeriodo($data_inicial_convertida, $data_final_convertida), $agrupamentos);
            }
        }

        self::removeExtremidades($resultado);

        return $resultado;
    }

    public static function removeExtremidades(array &$resultado) {
        //remove as extremidades não utilizadas
        foreach ($resultado as $index => &$agrupamentos) {
            //remove os itens vazios da esquerda, até encontrar algum item com valor
            foreach ($agrupamentos as $tempo => $valor) {
                if (empty($valor)) {
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
