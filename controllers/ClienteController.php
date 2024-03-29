<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ClienteSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends Controller {

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

    /**
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cliente model.
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
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Cliente();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            var_dump(Yii::$app->request->post());
//            echo '<br>';
//            echo '<br>';
//            echo '<br>';
//            var_dump($model);
//            exit();
            return $this->redirect(['index', 'id' => $model->pk_cliente]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->pk_cliente]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cliente model.
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
     * Recebe uma requisição ajax para retornar o código do cliente
     * @param type $id
     */
    public function actionPegarCodigoApp($id) {
        //se não tiver um código cadastrado no banco, gera uma número aleatório de 4 dígitos e salva
        $cliente = Cliente::findOne($id);
        $código = "";
        if (!empty($cliente)) {
            $count = 0;
            if (empty($cliente->codigo_cliente_app)) {
                //vai ficar tentando gerar número randomicos únicos até conseguir salvar
                
                do {
                    $cliente->codigo_cliente_app = rand(10000, 99999) . "";                    
                    $count++;
                } while (!$cliente->save() && $count < 1000); //vai tentar no máximo 1000 vezes
            }
            if ($count >= 1000) {
                return "Não foi possível gerar o código, tente novamente";
            } else {
                return $cliente->codigo_cliente_app;
            }
        } else {
            return "Não foi encontrado o cliente";
        }
    }

    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
