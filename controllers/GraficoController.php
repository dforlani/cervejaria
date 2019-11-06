<?php

namespace app\controllers;

use app\models\ItemVenda;
use yii\web\Controller;

/**
 * VendaController implements the CRUD actions for Venda model.
 */
class GraficoController extends Controller {

    public function actionTeste() {



        return $this->render('teste');
    }

    public function actionVendas() {

        $por_hora = isset($_GET['por_hora']) ? $_GET['por_hora'] : false;
        $por_dia = isset($_GET['por_dia']) ? $_GET['por_dia'] : false;
        $por_dia_semana = isset($_GET['por_dia_semana']) ? $_GET['por_dia_semana'] : false;
        $por_mes = isset($_GET['por_mes']) ? $_GET['por_mes'] : false;
        $por_produto = isset($_GET['por_produto']) ? $_GET['por_produto'] : false;
        $apenas_vendas_pagas = isset($_GET['apenas_vendas_pagas']) ? $_GET['apenas_vendas_pagas'] : false;
        $por_cliente = isset($_GET['por_cliente']) ? $_GET['por_cliente'] : false;
        $data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : '01/' . date('m/Y'); //primeiro dia do mês atual
        $data_final = isset($_GET['data_final']) ? $_GET['data_final'] : date('t/m/Y'); //último dia do mês atual


        $resultado = \app\models\ItemVendaSearch::searchGrafico($por_dia, $por_hora, $por_dia_semana, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final);


        return $this->render('vendas', [
                    'resultado' => $resultado,
                    'por_hora' => $por_hora,
                    'por_dia_semana' => $por_dia_semana,
                    'por_dia' => $por_dia,
                    'por_mes' => $por_mes,
                    'por_produto' => $por_produto,
                    'por_cliente' => $por_cliente,
                    'apenas_vendas_pagas' => $apenas_vendas_pagas,
                    'data_inicial' => $data_inicial,
                    'data_final' => $data_final
        ]);
    }

}
