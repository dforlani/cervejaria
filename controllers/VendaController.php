<?php

namespace app\controllers;

use app\models\Caixa;
use app\models\Configuracao;
use app\models\ItemVenda;
use app\models\ItemVendaSearch;
use app\models\Preco;
use app\models\PrecoSearch;
use app\models\Venda;
use app\models\VendaSearch;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
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
        $vendas = Venda::find()
                ->select(['*', 'IF(numero is null, 99999, numero) as numero_order', 'concat(COALESCE(nome,"") , COALESCE(nome_temp,""))  as denominacao_completa'])
                ->where(['estado' => Venda::$ESTADO_ABERTA])
                ->orderBy('numero_order, denominacao_completa')
                ->joinWith(['cliente','comanda'])->all();

        
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
        Yii::$app->response->format = Response::FORMAT_JSON;
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

        $precoModelItem = new PrecoSearch();

        $tapListProvider = $precoModelItem->search(['PrecoSearch' => ['tipo_cardapio' => Preco::$TIPO_CARDAPIO_TAP_LIST]], true, true, true, true);


        if (empty($id)) { //venda não iniciada
            $model = new Venda();
            $model->estado = Venda::$ESTADO_ABERTA;
            $model->valor_final = 0;
            $model->desconto = 0;
            $model->valor_total = 0;
            $modelItem = null;

            $dataProviderItem = null;
            $searchModelItem = null;
        } else {//adicionando novo item
            $model = $this->findModel($id);
            $modelItem = new ItemVenda();
            $modelItem->fk_venda = $model->pk_venda;
            $modelItem->quantidade = 1;
            $searchModelItem = new ItemVendaSearch();

            $dataProviderItem = $searchModelItem->search(['ItemVendaSearch' => ['fk_venda' => $model->pk_venda]]);
        }

        //só vai poder iniciar as vendas se tiver um caixa aberto. Caso seja uma venda paga, deixa o usuário entrar pra ver o que foi feito        
        if (!$model->isPaga() && !Caixa::hasCaixaAberto()) {
            Yii::$app->session->setFlash('warning', "O Caixa não está aberto. Abra o caixa para iniciar as vendas.");
            return $this->redirect(['/caixa']);
        }

        //cria a nova venda
        if ((!empty(Yii::$app->request->post('Venda'))) && ($model->load(Yii::$app->request->post()))) {

            $fiado = null;
            //pode ser que esteja iniciando um venda pra um cliente que tenha fiado, busca esta informação e retoma a venda
            if ((!empty(Yii::$app->request->post('Venda'))) && (!empty(Yii::$app->request->post('Venda')['fk_cliente']))) {
                $fiado = Venda::getVendaFiadoDoCliente(Yii::$app->request->post('Venda')['fk_cliente']);
                if ($fiado != null) {//vai reabrir uma venda fiado
                    Yii::$app->session->setFlash('error', "Cliente com fiado pendente, retomando comanda");
                    $fiado->estado = Venda::$ESTADO_ABERTA;
                    $model = $fiado;
                }
            }

            if ($model->save()) {
                return $this->redirect(['venda', 'id' => $model->pk_venda]);
            }
        } else {

            return $this->render('venda', [
                        'model' => $model,
                        'modelItem' => $modelItem,
                        'searchModelItem' => $searchModelItem,
                        'dataProviderItem' => $dataProviderItem,
                        'tapListProvider' => $tapListProvider
            ]);
        }
    }

    public function actionAdicionaItemForm($id = null) {

        $model = $this->findModel($id);

        $modelItem = new ItemVenda();
        $modelItem->fk_venda = $model->pk_venda;
        $modelItem->quantidade = 1;

        //insere um item
        if (!empty(Yii::$app->request->post('ItemVenda'))) {

            if ($modelItem->load(Yii::$app->request->post()) && $modelItem->save()) {

                $this->atualizaValoresVenda($model);

                $this->gerPDFVenda($model->pk_venda);
            }
        }
        return $this->redirect(['venda', 'id' => $id]);
    }

    public function actionAdicionaItemByTapList($pk_venda, $pk_preco) {
        $venda = $this->findModel($pk_venda);
        $preco = $this->findPreco($pk_preco);

        $itemVenda = new ItemVenda();
        $itemVenda->fk_venda = $venda->pk_venda;
        $itemVenda->fk_preco = $preco->pk_preco;
        $itemVenda->quantidade = 1; //sempre virá apenas 1 produto
        $itemVenda->preco_unitario = $preco->preco;


        if ($itemVenda->save()) {
            $this->atualizaValoresVenda($venda);

            $this->gerPDFVenda($venda->pk_venda);
            return $this->redirect(['venda', 'id' => $venda->pk_venda]);
        } else {
            print_r($itemVenda->getErrors());
        }
    }

    protected function atualizaValoresVenda($venda) {
        if (!$venda->save()) {
            Yii::$app->session->setFlash('error', "Ocorreu um problema ao tentar atualizar os Valores da venda. " . implode(",", $venda->getErrorSummary(true)));
        }
    }

    public function actionBuscaProduto($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $preco = Preco::findOne($id);
        if (!empty($preco)) {
            $estoque_disponivel = $preco->produto->getEntradaAtiva()->getEstoqueDisponivel();
            
            return ['preco' => $preco->preco, 'estoque_atual' => $estoque_disponivel . ' ' . $preco->produto->unidadeMedida->unidade_medida];
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

    /**
     * Updates an existing Venda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSalvarObservacao($id, $observacao) {
        $model = $this->findModel($id);
        $model->observacao = $observacao;

        if ($model->save()) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function actionPagamento($id) {

        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {


            if ($model->save()) {
                $this->gerPDFVenda($model->pk_venda);


                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true, 'estado' => $model->estado,];
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
        $pk_venda = $model->fk_venda;
        $fkVenda = $model->fk_venda;
        $model->delete();



        $venda = $this->findModel($fkVenda);
        $this->atualizaValoresVenda($venda);

        $this->gerPDFVenda($pk_venda);

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

    protected function findPreco($id) {
        if (($model = Preco::findOne($id)) !== null) {
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

    protected function gerHTMLVenda($pk_venda) {
        $model = $this->findModel($pk_venda);

        $content = $this->renderPartial('comprovante', [
            'model' => $model,
        ]);

        ob_start();
        echo $content;
        file_put_contents('yourpage.html', ob_get_contents());
    }

    /**
     * Caso o usuário tenha colocado nas configurações pra gerar PDF sobre cada venda, vai gerar e gravar na pasta selecionada
     * @param type $pk_venda
     * @return type
     */
    protected function gerPDFVenda($pk_venda) {
        if (Configuracao::isGravarPDF()) {
            $model = $this->findModel($pk_venda);

            $content = $this->renderPartial('comprovante', [
                'model' => $model,
            ]);


            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_CORE,
                
//                'tempPath'=>('../runtime/mpdf'),
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => Pdf::DEST_FILE,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting 
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}',
                'tempPath' => Yii::getAlias('@app/runtime/mpdf/'),
                'filename' => '../pdf/vendas/' . @$model->cliente->nome . '-' . @$model->comanda->numero . '-' . $model->getData_Venda_Formato_Linha() . '.pdf',
                // set mPDF properties on the fly
                'options' => ['title' => @$model->cliente->nome . ' - ' . $model->dt_venda,
                ],
                // call mPDF methods on the fly
                'methods' => [
                // 'SetHeader' => ['Krajee Report Header'],
                // 'SetFooter' => ['{PAGENO}'],
                ]
            ]);

            // return the pdf output as per the destination setting
            return $pdf->render();
        }
    }

}
