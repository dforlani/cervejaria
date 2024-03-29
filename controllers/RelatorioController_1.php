<?php

namespace app\controllers;

use app\models\VendaSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * VendaController implements the CRUD actions for Venda model.
 */
class RelatorioController extends Controller {

      public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//
//                ],
//            ],
        ];
    }
    
    public function actionVendas() {


        $por_dia = isset($_GET['por_dia']) ? $_GET['por_dia'] : false;
        $por_mes = isset($_GET['por_mes']) ? $_GET['por_mes'] : false;
        $por_produto = isset($_GET['por_produto']) ? $_GET['por_produto'] : false;
        $apenas_vendas_pagas = isset($_GET['apenas_vendas_pagas']) ? $_GET['apenas_vendas_pagas'] : false;
        $por_cliente = isset($_GET['por_cliente']) ? $_GET['por_cliente'] : false;
        $data_inicial = isset($_GET['data_inicial']) ? $_GET['data_inicial'] : '01/' . date('m/Y');//primeiro dia do mês atual
        $data_final = isset($_GET['data_final']) ? $_GET['data_final'] : date('t/m/Y');//último dia do mês atual


        $searchModel = new VendaSearch();      
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->searchRelatorio($por_dia, $por_mes, $por_produto, $apenas_vendas_pagas, $por_cliente, $data_inicial, $data_final);


        return $this->render('vendas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
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
