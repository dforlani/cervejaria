<?php

namespace app\controllers;

use app\models\Cliente;
use app\models\ItemCaixa;
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
    public function behaviors() {
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

        $cardapio = \app\models\Preco::find()->where("is_tap_list = 1", null)->orderBy('pos_tap_list')->all();

        $retorno = [];
        if (!empty($cardapio)) {
            foreach ($cardapio as $item) {
                $retorno[$item->pk_preco] = $item->getNomeProdutoPlusDenominacaoSemBarras();
            }
        }

        return $retorno;
    }

    /*
     * Recebe um pedido no formato JSON do app cliente
     * Vai ter o codigo do cliente, os itens e suas quantidades
     * 
     * ['id'=>id_cliente, 
     *  'itens' =>
     *      ['34'=>1] (código do preco e quantidade pedida
     * ]
     */

    public function actionPedir() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cliente_id = &$_POST['id'];

        if (!empty($cliente_id)) {

            $itens = &$_POST['itens'];
            if (!empty($itens)) {
                //primeiro cria um novo pedido

                $pedido = new \app\models\PedidoApp();
                $pedido->fk_cliente = $cliente_id;
                $pedido->status = \app\models\PedidoApp::$CONST_STATUS_ENVIADO;
                if ($pedido->save()) {

                    //agora inclui os itens solicitados
                    foreach ($itens as $fk_preco => $quantidade) {
                        $item_pedido = new \app\models\ItemPedidoApp();
                        $item_pedido->fk_pedido_app = $pedido->pk_pedido_app;
                        $item_pedido->fk_preco = $fk_preco;
                        $item_pedido->quantidade = $quantidade;

                        if (!$item_pedido->save()) {
                            $pedido->status = \app\models\PedidoApp::$CONST_STATUS_ERRO;
                            $pedido->save();

                            return "Ocorreu um erro ao enviar o seu pedido. Dirija-se ao caixa";
                        }
                    }

                    return ['id' => $pedido->pk_pedido_app, 'status' => $pedido->status];
                } else {
                    return "Não foi possível salvar o pedido. Dirija-se ao caixa";
                }
            }
        }
    }

    /*
     * Verifica o status do pedido e retorna 
     * 
     */

    public function actionStatus() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = &$_POST['id'];
        $model = \app\models\PedidoApp::findOne($id);
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
    public function actionPedidoAtendimento($id){
        $model = \app\models\PedidoApp::findOne($id);
        $model->status = \app\models\PedidoApp::$CONST_STATUS_EM_ATENDIMENTO;
        $model->save();
        return $this->renderAjax('pedido_atendimento', ['model'=>$model]);
    }
    
      
    /**
     * Vai vir uma requisição ajax de qualquer página e sera mostrado uma tela se tiver algum pedido esperando
     * 
     * @return type
     */
    public function actionPedidosEsperando(){
        $status = \app\models\PedidoApp::$CONST_STATUS_PRONTO;
        $pedidos = \app\models\PedidoApp::find()->where("status  not like '{$status}' order by pk_pedido_app ")->all();
        
        return $this->renderAjax('pedidos_esperando', ['pedidos'=>$pedidos]);
    }

    /**
     * O sistema ao ser informado que o pedido tá pronto, busca uma venda aberta do cliente
     * adiciona os itens na comanda dele e fecha
     * atualiza o pedido como pronto
     * 
     * @return type
     */
    public function actionConvertePedidoVenda(){
          Yii::$app->response->format = Response::FORMAT_JSON;
          
        $id_pedido = &$_POST['id'];
        $pedido = \app\models\PedidoApp::findOne($id_pedido);
        if(!empty($pedido)){
            $venda = \app\models\Venda::find()->where(['estado = "aberta" AND cliente.nome = :nome'], [':nome'=>$pedido->cliente->nome])->one();
            if(!empty($venda)){
                foreach($pedido->itemPedidoApps as $item_pedido){
                    $item_venda = new \app\models\ItemVenda();
                    $item_venda->fk_venda = $venda->pk_venda;
                    $item_venda->fk_preco = $item_pedido->fk_preco;
                    $item_venda->quantidade = $item_pedido->quantidade;
                    $item_venda->save();
                }
                
                $venda->save();//atualizar os totais
                
                $pedido->status = \app\models\PedidoApp::$CONST_STATUS_PRONTO;
                $pedido->save();
            }
        }
        
        return ['success'=>'true', 'id_venda'=>0];
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
