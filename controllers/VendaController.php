<?php

namespace app\controllers;

use app\models\ItemVenda;
use app\models\ItemVendaSearch;
use app\models\Preco;
use app\models\Venda;
use app\models\VendaSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    public function actionFolha() {
        $vendas = Venda::find()->where(['estado' => 'aberta'])->orderBy('nome')->joinWith(['cliente'])->all();

        return $this->render('folha', ['vendas' => $vendas]);
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
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model->load(Yii::$app->request->post());
        $model->valor_final = $model->valor_total - $model->desconto;
        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asCurrency($model->desconto), 'valor_final' => Yii::$app->formatter->asCurrency($model->valor_final), 'message' => ''];
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

            $modelItem = new ItemVenda();
            $modelItem->fk_venda = $model->pk_venda;
            $modelItem->quantidade = 1;
            $searchModelItem = new ItemVendaSearch();
            $dataProviderItem = $searchModelItem->search(['ItemVendaSearch' => ['fk_venda' => $model->pk_venda]]);
        }

        //salva a venda
        if ((!empty(Yii::$app->request->post('Venda'))) && ($model->load(Yii::$app->request->post()))) {


            if ((isset($_POST['Venda']['button'])) && $_POST['Venda']['button'] == 'pagar') {

                $model->estado = 'paga';
                date_default_timezone_set('America/Sao_Paulo');
                $model->dt_pagamento = date_create()->format('Y-m-d H:i:s');
                date('Y-m-d H:i:s');
                $model->save();
                return $this->redirect(['venda']);
            } else
            if ((isset($_POST['Venda']['button'])) && $_POST['Venda']['button'] == 'fiado') {
                $model->estado = 'fiado';
                $model->save();
                return $this->redirect(['venda']);
            } else if ($model->save()) {
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
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $preco = Preco::findOne($id);
        if (!empty($preco)) {
            return ['preco' => $preco->preco, 'estoque_atual' => ($preco->produto->estoque_inicial - $preco->produto->estoque_vendido) . ' ' . $preco->produto->unidadeMedida->unidade_medida];
        } else
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

    public function actionPagamento2($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['_pagamento', 'id' => $model->pk_venda]);
        }

        return $this->render('_pagamento', [
                    'model' => $model,
        ]);
    }

    public function actionPagamento($id) {

        $model = $this->findModel($id);
    

        if ($model->load(Yii::$app->request->post())) {
           
        
            if ($model->save()) {

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['venda', 'id' => $model->pk_venda]);
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_pagamento', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('_pagamento', [
                        'model' => $model,
            ]);
        }
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
        if (($model = ItemVenda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
