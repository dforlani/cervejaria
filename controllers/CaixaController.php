<?php

namespace app\controllers;

use app\models\Caixa;
use app\models\CaixaSearch;
use app\models\ItemCaixa;
use app\models\ItemCaixaSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CaixaController implements the CRUD actions for Caixa model.
 */
class CaixaController extends Controller {

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
     * Lists all Caixa models.
     * @return mixed
     */
    public function actionIndex() {

        $searchModel = null;
        $dataProvider = null;
        $caixa = null;
        $model = new ItemCaixa(); //item de abertura de caixa
        //fechamento de Caixa        
        if (isset($_POST['fechar_caixa']) && Caixa::hasCaixaAberto()) {
            $caixa = Caixa::getCaixaAberto();
            $caixa->estado = 'fechado';
            $caixa->dt_fechamento = new \yii\db\Expression('NOW()');
            $caixa->save();
        }

        //só permite abrir o caixa, se nenhum estiver aberto
        if (isset($_POST['abrir']) && !Caixa::hasCaixaAberto()) {
            $caixa = new Caixa();             
            $caixa->estado = 'aberto';
            if ($caixa->save()) {
                //cria o primeiro movimento o de abertura de caixa
                $model->load(Yii::$app->request->post());
                $model->fk_caixa = $caixa->pk_caixa;
                $model->tipo = $model->getStringAbertura();
                if (!$model->save()) {
                    $caixa->delete();
                    Yii::$app->session->setFlash('error', "O Caixa não pôde ser aberto. Ocorreram os seguintes errors: ".$model->getErrorSummary(true));
                   
                }else{
                     Yii::$app->session->setFlash('success', "Caixa aberto com sucesso. Pode iniciar as vendas.");
                }
            }
        } else {
            $caixa = Caixa::getCaixaAberto();
        }

        if (!empty($caixa)) {
            $searchModel = new ItemCaixaSearch();
            $dataProvider = $searchModel->search(['ItemCaixaSearch' => ['fk_caixa' => $caixa->pk_caixa]]);
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'caixa' => $caixa,
                    'model' => $model,
        ]);
    }

    public function actionVisualizar($id) {
        $caixa = Caixa::findOne($id);
        $searchModel = new ItemCaixaSearch();
        $dataProvider = $searchModel->search(['ItemCaixaSearch' => ['fk_caixa' => $caixa->pk_caixa]]);



        return $this->render('visualizar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'caixa' => $caixa,
        ]);
    }

    /**
     * Creates a new Caixa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($fk_caixa) {
        $model = new ItemCaixa();
        $model->fk_caixa = $fk_caixa;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['index']);
            }
        }

        // $model->fk_produto = $pk_produto;
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Caixa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->redirect(['index']);
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionFechados() {
        $searchModel = new CaixaSearch();
        $_GET['CaixaSearch']['estado'] = 'fechado'; //só vai mostrar os caixas fechados
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('fechados', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Reabre um caixa se não tiver nenhum aberto
     * @param type $id
     * @return type
     */
    public function actionReabrir($id) {
        if (Caixa::hasCaixaAberto()) {
            Yii::$app->session->setFlash('warning', "Já existe um caixa aberto. Feche o caixa atual para reabrir o solicitado. Só pode haver um Caixa aberto por vez.");
            return $this->redirect(['fechados']);
        } else {
            $model = Caixa::findOne($id);
            $model->estado = 'aberto';
            $model->dt_fechamento = null;
            $model->save();
            return $this->redirect(['/caixa']);
        }
    }

    /**
     * Deletes an existing Caixa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    protected function findModelCaixa($id) {
        if (($model = Caixa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
