<?php

namespace app\controllers;

use app\models\ItemCaixa;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * PedidowsController controla os pedidos feitos por aplicativo e o atendimentos a eles
 */
class PedidowsController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'teste' => ['GET'],
                ],
            ],
        ];
    }

    public function actionTeste() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [1, 2, 3, 4];
    }

    /**
     * Finds the Caixa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItemCaixa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ItemCaixa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
