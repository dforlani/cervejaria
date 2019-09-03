<?php

namespace app\controllers;

use Yii;
use app\models\Venda;
use app\models\VendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VendaController implements the CRUD actions for Venda model.
 */
class RelatorioController extends Controller {

    public function actionVendas() {

        $por_dia = isset($_GET['por_dia']) ? $_GET['por_dia'] : false;
        $por_mes = isset($_GET['por_mes']) ? $_GET['por_mes'] : false;
        $por_produto = isset($_GET['por_produto']) ? $_GET['por_produto'] : false;
        $apenas_vendas_pagas = isset($_GET['apenas_vendas_pagas']) ? $_GET['apenas_vendas_pagas'] : false;

        $searchModel = new VendaSearch();
        $dataProvider = $searchModel->searchRelatorio($por_dia, $por_mes, $por_produto, $apenas_vendas_pagas);

        return $this->render('vendas', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'por_dia' => $por_dia,
                    'por_mes' => $por_mes,
                    'por_produto' => $por_produto,
                    'apenas_vendas_pagas' => $apenas_vendas_pagas
        ]);
    }

}
