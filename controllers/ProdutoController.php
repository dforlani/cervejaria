<?php

namespace app\controllers;

use app\models\Preco;
use app\models\PrecoSearch;
use app\models\Produto;
use app\models\ProdutoSearch;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ProdutoController implements the CRUD actions for Produto model.
 */
class ProdutoController extends Controller {

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
     * Lists all Produto models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProdutoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Produto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionAlteraPreco($id) {
        $model = $this->findModelPreco($id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_GET['grid'])) {//temos duas telas que enviam de formas diferentes a requisição
          //  $preco['Preco'] = array_shift($_POST['Preco']);
            $preco['Preco']['preco'] = $_POST['preco']; 
            $model->load($preco);
        } else {
            $model->load(Yii::$app->request->post());
        }
      //  print_r($preco);
        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asCurrency($model->preco), 'message' => ''];
        } else {
            return ['output' => 'Zicou e não salvou', 'message' => ''];
        }
    }

    /**
     * Creates a new Produto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Produto();
        $model->is_vendavel = true;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('warning', "Produto inserido, cadastre as formas de venda.");
            $this->redirect(['update', 'id' => $model->pk_produto]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionGerarPdf() {
        $precos = Preco::find()->where('is_vendavel IS TRUE AND (dt_vencimento >= CURDATE() OR dt_vencimento IS NULL) AND codigo_barras IS NOT NULL AND codigo_barras != "" ')->joinWith('produto')->orderBy('nome, denominacao')->all();
        $codigos = array();

        foreach ($precos as $modelPreco) {
            $codigos[] = $this->renderPartial('preco/codigo_barras', ['modelPreco' => $modelPreco]);
        }
        $content = $this->renderPartial('preco/pdf_codigos_barra', ['codigos' => $codigos]);
        //echo $content;
        //exit();
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            // 'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
            // 'SetHeader' => ['Krajee Report Header'],
            // 'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Updates an existing Produto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $searchModelPreco = new PrecoSearch();
        $dataProviderPreco = $searchModelPreco->search(['PrecoSearch' => ['fk_produto' => $model->pk_produto]]);


        return $this->render('update', [
                    'model' => $model,
                    'searchModelPreco' => $searchModelPreco,
                    'dataProviderPreco' => $dataProviderPreco,
        ]);
    }

    public function actionTapList() {

        if (!empty($_POST['Preco'])) {
            //vindo de um pedido de inclusão na tap list
            if (isset($_POST['Preco']['pk_preco'])) {
                $model = $this->findModelPreco($_POST['Preco']['pk_preco']);

                //se já estiver na tap list, não vai adicionar novamente
                if (!$model->is_tap_list) {
                    $pos = Preco::getMaiorTapList();
                    if (empty($pos))
                        $pos = 0;

                    $model->is_tap_list = 1;
                    $model->pos_tap_list = $pos + 1;
                }
            }else
            if (isset($_POST['editableKey'])) {
                $model = $this->findModelPreco($_POST['editableKey']);

                $model->pos_tap_list = $_POST['Preco'][$_POST['editableIndex']]['pos_tap_list'];
                $model->save();
                $mensagem = '';

                if (!empty($model->getErrors())) {
                    $mensagem = implode(', ', $model->getErrorSummary(true));
                }

                echo Json::encode(['output' => $model->pos_tap_list, 'message' => $mensagem]);

                return;
            }
        } else {
            $model = new Preco();
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['tap-list']);
        }



        $searchModel = new PrecoSearch();
        if (empty($_GET['sort']))
            $_GET['sort'] = 'pos_tap_list';

        $dataProvider = $searchModel->search(['PrecoSearch' => ['is_tap_list' => 1],]);


        return $this->render('tap_list', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Preco model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePrecobkp($pk_produto) {
        $model = new Preco();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $pk_produto]);
        }
        $model->fk_produto = $pk_produto;

        return $this->render('preco/create', [
                    'model' => $model,
        ]);
    }

    public function actionCreatePreco($pk_produto) {
        $model = new Preco();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['update', 'id' => $pk_produto]);
            }
        }

        $model->fk_produto = $pk_produto;
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('preco/create', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('preco/create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionUpdatePreco($pk_preco = null) {

        $model = $this->findModelPreco($pk_preco);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['update', 'id' => $model->fk_produto]);
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('preco/update', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('preco/update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Preco model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdatePrecobkp($pk_preco = null) {

        $model = $this->findModelPreco($pk_preco);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->fk_produto]);
        }

        return $this->render('preco/update', [
                    'model' => $model,
        ]);
    }

    public function actionDeletePreco($pk_preco) {
        $modelPreco = $this->findModelPreco($pk_preco);
        $pk_produto = $modelPreco->fk_produto;
        $modelPreco->delete();
        return $this->redirect(['update', 'id' => $pk_produto]);
    }

    public function actionCodigoBarras($pk_preco) {
        $modelPreco = $this->findModelPreco($pk_preco);

        return $this->renderPartial('preco/codigo_barras', ['modelPreco' => $modelPreco]);
    }

    /**
     * Deletes an existing Produto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionRemoverTapList($id) {
        $model = $this->findModelPreco($id);
        $model->is_tap_list = 0;
        $model->pos_tap_list = null;
        $model->save();


        return $this->redirect(['tap-list']);
    }

    /**
     * Finds the Produto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Produto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Produto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelPreco($id) {
        if (($model = Preco::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
