<?php

namespace app\controllers;

use app\models\ItemCaixa;
use app\models\ItemPedidoApp;
use app\models\ItemVenda;
use app\models\PedidoApp;
use app\models\PedidoAppSearch;
use app\models\Preco;
use app\models\Venda;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * PedidowsController controla os pedidos feitos por aplicativo e o atendimentos a eles
 */
class PedidoappController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors1kjfkdnkfjdnkfnk() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'cardapio' => ['GET'],
                    'pedir' => ['POST'],
                    'status' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Retorna o cardápio da Tap List para o App do Cliente
     * @return type
     */
    public function actionCardapio() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cardapio = Preco::find()->where("is_tap_list = 1", null)->orderBy('pos_tap_list')->all();

        $retorno = [];
        if (!empty($cardapio)) {
            foreach ($cardapio as $item) {

                $retorno[] = ['fk_preco' => $item->pk_preco,
                    'denominacao' => $item->getNomeProdutoPlusDenominacaoSemBarras(),
                    'quantidade' => 0,
					'preco'=>$item->preco
                ];
            }
        }

        return $retorno;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {

        $this->enableCsrfValidation = false;


        return parent::beforeAction($action);
    }

    public function actionTest() {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $_REQUEST['oi'] = 'preprogramadonoservidor';
        // return  [file_get_contents( 'php://input' ) => 'kmlklmklm'];
        //return getallheaders();
        $_POST['opopo'] = 99;
        return $_POST;
    }

    public function actionCancelaPedidoAplicativo() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id_venda = &$_POST['id_venda'];
        $id_pedido = &$_POST['id_pedido'];
        $pedido = PedidoApp::findOne($id_pedido);


        if (!empty($pedido)) {
            $pedido->status = PedidoApp::$CONST_STATUS_CANCELADO_PELO_ATENDENTE;
            if ($pedido->save())
                return ['success' => 'true'];
        }

        return ['success' => 'false'];
    }

    /*
     * Recebe um pedido no formato JSON do app cliente
     * Vai ter o codigo do cliente, os itens e suas quantidades
     * 
     * ['pk_venda'=>pk_venda, 
     *  'itens' =>
     *      [
      ['pk_preco'=>pk_preco,
     * 		 'denominacao'=>denominacao
     * 		'quantidade'=>quantidade],
     * ] (código do preco e quantidade pedida
     * ]
     */

    public function actionPedir($codigo_cliente_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($codigo_cliente_app)) {
			

            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->joinWith('cliente')->where(['codigo_cliente_app' => $codigo_cliente_app, 'estado' => 'aberta'])->one();
            if (!empty($venda)) {
				


                $itens = &$_POST['itens'];

                if (!empty($itens)) {
                    //primeiro cria um novo pedido

                    $pedido = new PedidoApp();
                    $pedido->fk_cliente = $venda->fk_cliente;
                    $pedido->fk_venda = $venda->pk_venda;

                    $pedido->status = PedidoApp::$CONST_STATUS_ENVIADO;

                    if ($pedido->save()) {

                        //agora inclui os itens solicitados
                        foreach ($itens as $fk_preco => $item) {
                            $item_pedido = new ItemPedidoApp();
                            $item_pedido->fk_pedido_app = $pedido->pk_pedido_app;
                            $item_pedido->fk_preco = $item['fk_preco'];
                            $item_pedido->quantidade = $item['quantidade'];

                            if (!$item_pedido->save()) {
                                $pedido->status = PedidoApp::$CONST_STATUS_ERRO;
                                $pedido->save();
                                return "Ocorreu um erro ao adicionar os itens do pedido. Dirija-se ao caixa" . implode(',', $item_pedido->getErrorSummary(true));
                            }
                        }

                        return ['pk_pedido_app' => $pedido->pk_pedido_app, 'status' => $pedido->status];
                    } else {
                        return "Não foi possível salvar o pedido. Dirija-se ao caixa" . implode(',', $pedido->getErrorSummary(true));
                    }
                }else{
					return ['pk_pedido_app' => -1, 'status' => "Nenhum item foi solicitado."];
				}
            } else {
                return ['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."];
            }
        }
    }

    /**
     * Lists all Comanda models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PedidoAppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * Verifica o status do pedido e retorna 
     * 
     */

    public function actionVerificarStatusPedido($pk_pedido_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = PedidoApp::findOne($pk_pedido_app);
        if (!empty($model)) {
            return ['pk_pedido_app' => $model->pk_pedido_app, 'status' => $model->status];
        } else {
            return ['status' => 'Não encontrado'];
        }
    }

    /*
     * Verifica o status do pedido e retorna 
     * 
     */

    public function actionStatus() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = &$_POST['id'];
        $model = PedidoApp::findOne($id);
        if (!empty($model)) {
            return ['status' => $model->status];
        } else {
            return ['status' => 'Não encontrado'];
        }
    }

    public function actionTeste() {
        return $this->render('teste_pedir');
    }

    /**
     * Vai vir uma requisição ajax de qualquer página e sera mostrado uma tela se tiver algum pedido esperando
     * 
     * @return type
     */
    public function actionPedidoAtendimento($id) {
        $model = PedidoApp::findOne($id);
        $model->status = PedidoApp::$CONST_STATUS_EM_ATENDIMENTO;
        $model->save();
        return $this->renderAjax('pedido_atendimento', ['model' => $model]);
    }

    /**
     * Vai vir uma requisição ajax de qualquer página e sera mostrado uma tela se tiver algum pedido esperando
     * 
     * @return type
     */
    public function actionPedidosEsperando() {
        $enviado = PedidoApp::$CONST_STATUS_ENVIADO;
        $atendimento = PedidoApp::$CONST_STATUS_EM_ATENDIMENTO;
        $pedidos = PedidoApp::find()->where("status  like '{$enviado}' OR status like '{$atendimento}' order by pk_pedido_app ")->all();
        if (count($pedidos) > 0)
            return $this->renderAjax('pedidos_esperando', ['pedidos' => $pedidos]);
        else
            return "";
    }

    /**
     * O sistema ao ser informado que o pedido tá pronto, busca uma venda aberta do cliente
     * adiciona os itens na comanda dele e fecha
     * atualiza o pedido como pronto
     * 
     * @return type
     */
    public function actionConvertePedidoVenda() {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id_venda = &$_POST['id_venda'];
        $id_pedido = &$_POST['id_pedido'];
        $pedido = PedidoApp::findOne($id_pedido);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $salvoTodos = true;

        if (!empty($pedido)) {
            $venda = Venda::findOne($id_venda);
            if (!empty($venda)) {
                foreach ($pedido->itensPedidoApp as $item_pedido) {

                    $item_venda = new ItemVenda();
                    $item_venda->fk_venda = $venda->pk_venda;
                    $item_venda->fk_preco = $item_pedido->fk_preco;
                    $item_venda->quantidade = $item_pedido->quantidade;
                    $item_venda->preco_unitario = $item_pedido->preco->preco;
                    $salvoTodos = $salvoTodos && $item_venda->save();
                }

                $salvoTodos = $salvoTodos && $venda->save(); //atualizar os totais

                $pedido->status = PedidoApp::$CONST_STATUS_PRONTO;
                $salvoTodos = $salvoTodos && $pedido->save();
                if ($salvoTodos) {
                    $transaction->commit();
                    return ['success' => 'true'];
                } else {
                    $transaction->rollBack();
                    return ['success' => 'false'];
                }
            }
        }
        $transaction->rollBack();
        return ['success' => 'false'];
    }

	public function actionRequisitaPedidosComandaAberta($codigo_cliente_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($codigo_cliente_app)) {

            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->joinWith('cliente')->where(['codigo_cliente_app' => $codigo_cliente_app, 'estado' => 'aberta'])->one();
            if (!empty($venda)) {
				$pedidos = $venda->pedidosApp;
				if(!empty($pedidos)){
					$retorno = [];
					foreach($pedidos as $pedido){
						$itens = $pedido->itensPedidoApp;
						$itens_array = [];
							foreach($itens as $item){
								$aux['fk_preco'] = $item->fk_preco;
								$aux['denominacao'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
								$aux['quantidade'] = $item->quantidade;
								$aux['preco'] = $item->preco->preco;
								
								$itens_array[] = $aux;
							}
							
						$retorno[] = ['status' => $pedido->status,
							'pk_pedido_app' => $pedido->pk_pedido_app,
							'dt_pedido'=> Yii::$app->formatter->asDateTime($pedido->dt_pedido),
							'itens' => $itens_array
							
						
						];
						
						//$retorno[$pedido->pk_pedido_app]['itens'] = [];
							
					}
					return $retorno;
				
				

                }
            } else {
                return ['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."];
            }
        }
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
