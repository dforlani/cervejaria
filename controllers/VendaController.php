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
class VendaController extends Controller {

    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Venda models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new VendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Venda model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Venda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Venda();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pk_venda]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionAlteraDesconto($id) {
        $model = $this->findModel($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model->load(Yii::$app->request->post());
        $model->valor_final = $model->valor_total - $model->desconto;
        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asCurrency($model->desconto), 'valor_final'=>Yii::$app->formatter->asCurrency($model->valor_final), 'message' => ''];
        } else {
            return ['output' => '', 'message' => ''];
        }
    }

    /**
     * Creates a new Venda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionVenda($id = null) {

        if (empty($id)) {
            $model = new Venda();
            $model->estado = 'aberta';
            $model->valor_final = 0;
            $model->desconto = 0;
            $model->valor_total = 0;
            $modelItem = null;

            $dataProviderItem = null;
            $searchModelItem = null;
        } else {
            $model = $this->findModel($id);

            $modelItem = new \app\models\ItemVenda();
            $modelItem->fk_venda = $model->pk_venda;
            $modelItem->quantidade = 1;
            $searchModelItem = new \app\models\ItemVendaSearch();
            $dataProviderItem = $searchModelItem->search(['ItemVendaSearch' => ['fk_venda' => $model->pk_venda]]);
        }

        //salva a venda
        if (!empty(Yii::$app->request->post('Venda'))) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if (isset($_POST['bt_pagar'])) {
                    $model->estado = 'paga';
                    date_default_timezone_set('America/Sao_Paulo');
                    $model->dt_pagamento = date_create()->format('Y-m-d H:i:s');
                    date('Y-m-d H:i:s');
                    $model->save();
                    return $this->redirect(['venda']);
                } else
                if (isset($_POST['bt_fiado'])) {
                    $model->estado = 'fiado';
                    $model->save();
                    return $this->redirect(['venda']);
                }
                return $this->redirect(['venda', 'id' => $model->pk_venda]);
            }
        }

        //insere um item
        if (!empty(Yii::$app->request->post('ItemVenda'))) {
            $modelItem->preco_final = $modelItem->preco_unitario * $modelItem->quantidade;
            if ($modelItem->load(Yii::$app->request->post()) && $modelItem->save()) {
                $model->atualizaValorFinal();
                return $this->redirect(['venda', 'id' => $model->pk_venda]);
            }
        }


        return $this->render('venda', [
                    'model' => $model,
                    'modelItem' => $modelItem,
                    'searchModelItem' => $searchModelItem,
                    'dataProviderItem' => $dataProviderItem
        ]);
    }

    public function actionBuscaProduto($id) {
        $preco = \app\models\Preco::findOne($id);
        if (!empty($preco))
            return $preco->preco;
        else
            return '';
    }

    /**
     * Updates an existing Venda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pk_venda]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Venda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteItem($id) {
        $model = $this->findModelItem($id);
        $fkVenda = $model->fk_venda;
        $model->delete();

        $venda = $this->findModel($fkVenda);
        $venda->atualizaValorFinal();

        return $this->redirect(['venda', 'id' => $fkVenda]);
    }

    public function actionComprovante($id) {
        $model = $this->findModel($id);
        return $this->renderPartial('comprovante', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the Venda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Venda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Venda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelItem($id) {
        if (($model = \app\models\ItemVenda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
