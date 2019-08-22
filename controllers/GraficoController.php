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
class GraficoController extends Controller {

    public function actionTeste() {
        


        return $this->render('teste');
    }

}
