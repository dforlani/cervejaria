<?php

namespace app\controllers;

use app\models\Cerveja;
use app\models\ItemVendaSearch;
use app\models\VendaSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * VendaController implements the CRUD actions for Venda model.
 */
class GraficoController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionVendas() {

        $apenas_cervejas_ativas = isset($_GET['apenas_cervejas_ativas']) ? $_GET['apenas_cervejas_ativas'] : false;
        $por_gasto = isset($_GET['por_gasto']) ? $_GET['por_gasto'] : false;
        $por_litro = isset($_GET['por_litro']) ? $_GET['por_litro'] : false;
        $por_hora = isset($_GET['por_hora']) ? $_GET['por_hora'] : false;
        $por_dia = isset($_GET['por_dia']) ? $_GET['por_dia'] : false;
        $por_dia_semana = isset($_GET['por_dia_semana']) ? $_GET['por_dia_semana'] : false;
        $por_mes = isset($_GET['por_mes']) ? $_GET['por_mes'] : false;
        $por_mes_agregado = isset($_GET['por_mes_agregado']) ? $_GET['por_mes_agregado'] : false;
        $por_produto = isset($_GET['por_produto']) ? $_GET['por_produto'] : false;
        $apenas_vendas_pagas = isset($_GET['apenas_vendas_pagas']) ? $_GET['apenas_vendas_pagas'] : false;
        $por_cliente = isset($_GET['por_cliente']) ? $_GET['por_cliente'] : false;
        $por_forma_venda = isset($_GET['por_forma_venda']) ? $_GET['por_forma_venda'] : false;
        $data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : '01/' . date('m/Y'); //primeiro dia do mês atual
        $data_final = isset($_GET['data_final']) ? $_GET['data_final'] : date('t/m/Y'); //último dia do mês atual
        $cervejas_selecionadas = isset($_GET['cervejas_selecionadas']) ? $_GET['cervejas_selecionadas'] : []; //último dia do mês atual

        if (empty($_GET)) {
            $por_litro = true;
            $por_produto = true;
            $por_dia = true;
        }

        $cervejas = Cerveja::getArrayCervejas();

        $graficoResultado = ItemVendaSearch::searchGrafico($por_dia, $por_hora, $por_dia_semana,
                        $por_mes_agregado, $por_mes, $por_produto, $apenas_vendas_pagas,
                        $por_cliente, $data_inicial, $data_final, $por_gasto, $por_litro,
                        $cervejas_selecionadas, $por_forma_venda, $apenas_cervejas_ativas);



        return $this->render('vendas', [
                    'apenas_cervejas_ativas' => $apenas_cervejas_ativas,
                    'por_forma_venda' => $por_forma_venda,
                    'cervejas' => $cervejas,
                    'cervejas_selecionadas' => $cervejas_selecionadas,
                    'por_gasto' => $por_gasto,
                    'por_litro' => $por_litro,
                    'graficoResultado' => $graficoResultado,
                    'por_hora' => $por_hora,
                    'por_dia_semana' => $por_dia_semana,
                    'por_dia' => $por_dia,
                    'por_mes_agregado' => $por_mes_agregado,
                    'por_mes' => $por_mes,
                    'por_produto' => $por_produto,
                    'por_cliente' => $por_cliente,
                    'apenas_vendas_pagas' => $apenas_vendas_pagas,
                    'data_inicial' => $data_inicial,
                    'data_final' => $data_final
        ]);
    }

}
