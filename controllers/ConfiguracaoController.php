<?php

namespace app\controllers;

use app\models\Caixa;
use app\models\Configuracao;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CaixaController implements the CRUD actions for Caixa model.
 */
class ConfiguracaoController extends Controller {

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

        $configuracoes = [];
        //configuracao pra gerar PDF sobre todas as vendas
        $configuracoes['pdf_todas_paginas'] = Configuracao::getConfiguracaoByTipo("pdf_todas_paginas");

        //configuracao pra gerar PDF sobre todas as vendas
        $configuracoes['path_pdf_todas_paginas'] = Configuracao::getConfiguracaoByTipo("path_pdf_todas_paginas");

        //configuracao para mostrar ou não o botão de fiado
        $configuracoes['is_mostrar_botao_fiado'] = Configuracao::getConfiguracaoByTipo("is_mostrar_botao_fiado");


        //solicitação de salvar
        if (!empty(Yii::$app->request->get())) {
            //configurações de pdf_todas_paginas
            $model = $configuracoes['pdf_todas_paginas'];
            $model->valor = Yii::$app->request->get('conf_pdf_todas_paginas', '0');
            $model->save();

            //configurações de pdf_todas_paginas
            $model = $configuracoes['is_mostrar_botao_fiado'];
            $model->valor = Yii::$app->request->get('is_mostrar_botao_fiado', '0');
            $model->save();
        }



        return $this->render('index', ['configuracoes' => $configuracoes
        ]);
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
     * @return Caixa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Caixa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
    public function actionBackup() {
        $data = date('Y-m-d-H-i-s');
        $pasta = "../backup/dump_$data.sql";
   
        $msg = "";
        if (@$_GET['gerar'] == 1) {
          //  use Ifsnop\Mysqldump as IMysqldump;
          
            try {
                $dump = new \Ifsnop\Mysqldump\Mysqldump('mysql:host=localhost;dbname=fabrica', 'root', '');
                $dump->start($pasta);
                 $msg =  "Backup realizado com sucesso na pasta do sistema em $pasta!";
            } catch (\Exception $e) {
                $msg = 'mysqldump-php error: ' . $e->getMessage();
            }
           
        }elseif (@$_GET['abrir_pasta'] == 1) {
            exec ("explorer ". realpath('../backup/'));
           
        }
        

        return $this->render('backup', ['msg'=>$msg]);
    }

}
