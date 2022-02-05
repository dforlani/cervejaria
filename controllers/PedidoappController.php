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
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\MCrypt;

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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['pedidos-esperando', 'app-get-cardapio', 'app-get-comanda-aberta', 'app-get-tap-list', 'app-pedir', 'app-requisita-pedidos-comanda-aberta', 'app-verificar-status-pedido']
                    ],
                    [
                        'allow' => true,
//                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Retorna o cardápio da Tap List para o App do Cliente
     * @return type
     */
    public function actionAppGetTapList() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cardapio = Preco::find()->where("tipo_cardapio like :tipo_cardapio", [':tipo_cardapio' => Preco::$TIPO_CARDAPIO_TAP_LIST])->orderBy('pos_cardapio')->all();

        $retorno = [];
        if (!empty($cardapio)) {
            foreach ($cardapio as $item) {

                $retorno[] = ['fk_preco' => $item->pk_preco,
                    'denominacao' => $item->denominacao,
                    'nome' => $item->produto->nome,
                    'quantidade' => 0,
                    'preco' => $item->preco
                ];
            }
        }

        return $retorno;
    }

    public function actionTeste() {
        $mcrypt = new MCrypt();
#Encrypt
        $encrypted = $mcrypt->encrypt("Text to Encrypt");
        echo $encrypted;
#Decrypt
        $decrypted = $mcrypt->decrypt($encrypted);
        echo '<br>';
        echo $decrypted;
    }

    /**
     * Retorna o cardápio do tipo cardapio para o App do Cliente
     * @return type
     */
    public function actionAppGetCardapio() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cardapio = Preco::find()->where("tipo_cardapio like :tipo_cardapio", [':tipo_cardapio' => Preco::$TIPO_CARDAPIO_CARDAPIO])->orderBy('pos_cardapio')->all();

        $retorno = [];
        if (!empty($cardapio)) {
            foreach ($cardapio as $item) {

                $retorno[] = ['fk_preco' => $item->pk_preco,
                    'denominacao' => $item->denominacao,
                    'nome' => $item->produto->nome,
                    'quantidade' => 0,
                    'preco' => $item->preco
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

    public function decriptografar($texto) {

        $mcrypt = new MCrypt();
        return $mcrypt->decrypt($texto);
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

    public function actionAppPedir($codigo_cliente_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($codigo_cliente_app)) {


            $codigo_cliente_app = $this->decriptografar($codigo_cliente_app);


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
                } else {
                    return ['pk_pedido_app' => -1, 'status' => "Nenhum item foi solicitado."];
                }
            } else {
                return ['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."];
            }
        }
    }

    /**
     * Versão do aplicativo do Garçom pra receber pedidos
     * @param type $pkVenda
     * @return type
     */
    public function actionAppPedirGarcom($pkVenda) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($pkVenda)) {


            $pkVenda = $this->decriptografar($pkVenda);


            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->where(['pk_venda' => $pkVenda, 'estado' => 'aberta'])->one();
            if (!empty($venda)) {


				$observacoes = &$_POST['observacoes'];
                $itens = &$_POST['itens'];

                if (!empty($itens)) {
                    //primeiro cria um novo pedido

                    $pedido = new PedidoApp();
                    $pedido->fk_cliente = $venda->fk_cliente;
                    $pedido->fk_venda = $venda->pk_venda;
					$pedido->observacoes = $observacoes;

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
                } else {
                    return ['pk_pedido_app' => -1, 'status' => "Nenhum item foi solicitado."];
                }
            } else {
                return ['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."];
            }
        }
    }

    /*
     * Retorna a comanda do cliente
     * 
     * return
     * ['pk_venda'=>pk_venda, 
     * 'valor_total' => valor_total
     * 'valor_pago' => valor_pago
     *  'itens' =>
     *      [
     *        ['pk_preco'=>pk_preco,
     * 		 'denominacao'=>denominacao
     * 		'quantidade'=>quantidade],
     *        ] 
     *      ]
     * ]
     */

    public function actionAppGetComandaAberta($codigo_cliente_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($codigo_cliente_app)) {
            $codigo_cliente_app = $this->decriptografar($codigo_cliente_app);
            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->joinWith('cliente')->where(['codigo_cliente_app' => $codigo_cliente_app, 'estado' => 'aberta'])->one();
            if (!empty($venda)) {
                $valor_pago = $venda->valor_pago_credito + $venda->valor_pago_debito + $venda->valor_pago_dinheiro;

                $itens_array = [];
                if (!empty($venda->itensVenda)) {
                    foreach ($venda->itensVenda as $item) {
                        $itens_array[$item->fk_preco]['fk_preco'] = $item->fk_preco;
                        if (!$item->is_desconto_promocional) {//pra separar descontos de itens comprados
                            $itens_array[$item->fk_preco]['denominacao'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $itens_array[$item->fk_preco]['quantidade'] = (isset($itens_array[$item->fk_preco]['quantidade']) ? $itens_array[$item->fk_preco]['quantidade'] + $item->quantidade : $item->quantidade);
                            $itens_array[$item->fk_preco]['preco'] = $item->preco_unitario;
                            $itens_array[$item->fk_preco]['precoTotal'] = (isset($itens_array[$item->fk_preco]['precoTotal']) ? $itens_array[$item->fk_preco]['precoTotal'] + $item->preco_final : $item->preco_final + 0 );
                        } else {
                            $itens_array[$item->fk_preco . 'promocao']['denominacao'] = 'Desconto - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $itens_array[$item->fk_preco . 'promocao']['quantidade'] = (isset($itens_array[$item->fk_preco . 'promocao']['quantidade']) ? $itens_array[$item->fk_preco . 'promocao']['quantidade'] + $item->quantidade : $item->quantidade);
                            $itens_array[$item->fk_preco . 'promocao']['preco'] = $item->preco_unitario;
                            $itens_array[$item->fk_preco . 'promocao']['precoTotal'] = (isset($itens_array[$item->fk_preco . 'promocao']['precoTotal']) ? $itens_array[$item->fk_preco . 'promocao']['precoTotal'] + $item->preco_final : $item->preco_final + 0 );
                        }
                    }
                }

                //formata para o padrão brasileiro de moeda
                if (!empty($itens_array)) {
                    foreach ($itens_array as $item_array) {
                        $itens_array[$item_array['fk_preco']]['precoTotal'] = \Yii::$app->formatter->asCurrency($item_array['precoTotal']);
                    }
                }

                $itens_array = array_values($itens_array);
                return ['pk_venda' => $venda->pk_venda, 'valor_total' => \Yii::$app->formatter->asCurrency($venda->valor_total), 'valor_pago' => \Yii::$app->formatter->asCurrency($valor_pago), 'itensVenda' => $itens_array];
            } else {
                return ['pk_venda' => -1, 'status' => "É preciso ter uma comanda aberta. Dirija-se ao caixa."];
            }
        }
    }

    /**
     * Retorna os totais da comanda do cliente pro App do Garçom
     * 
     * @param type $codigo_cliente_app
     * @return type
     */
    public function actionAppGetTotaisComandaGarcom($pkVenda) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($pkVenda)) {
            $pkVenda = $this->decriptografar($pkVenda);
            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->where(['pk_venda' => $pkVenda, 'estado' => 'aberta'])->one();
            if (!empty($venda)) {
                $valor_pago = $venda->valor_pago_credito + $venda->valor_pago_debito + $venda->valor_pago_dinheiro;

                $itens_array = [];
                if (!empty($venda->itensVenda)) {
                    foreach ($venda->itensVenda as $item) {
                        $itens_array[$item->fk_preco]['fk_preco'] = $item->fk_preco;
                        if (!$item->is_desconto_promocional) {//pra separar descontos de itens comprados
                            $itens_array[$item->fk_preco]['denominacao'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $itens_array[$item->fk_preco]['quantidade'] = (isset($itens_array[$item->fk_preco]['quantidade']) ? $itens_array[$item->fk_preco]['quantidade'] + $item->quantidade : $item->quantidade);
                            $itens_array[$item->fk_preco]['preco'] = $item->preco_unitario;
                            $itens_array[$item->fk_preco]['precoTotal'] = (isset($itens_array[$item->fk_preco]['precoTotal']) ? $itens_array[$item->fk_preco]['precoTotal'] + $item->preco_final : $item->preco_final + 0 );
                        } else {
                            $itens_array[$item->fk_preco . 'promocao']['denominacao'] = 'Desconto - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $itens_array[$item->fk_preco . 'promocao']['quantidade'] = (isset($itens_array[$item->fk_preco . 'promocao']['quantidade']) ? $itens_array[$item->fk_preco . 'promocao']['quantidade'] + $item->quantidade : $item->quantidade);
                            $itens_array[$item->fk_preco . 'promocao']['preco'] = $item->preco_unitario;
                            $itens_array[$item->fk_preco . 'promocao']['precoTotal'] = (isset($itens_array[$item->fk_preco . 'promocao']['precoTotal']) ? $itens_array[$item->fk_preco . 'promocao']['precoTotal'] + $item->preco_final : $item->preco_final + 0 );
                        }
                    }
                }

                //formata para o padrão brasileiro de moeda
                if (!empty($itens_array)) {
                    foreach ($itens_array as $item_array) {
                        $itens_array[$item_array['fk_preco']]['precoTotal'] = \Yii::$app->formatter->asCurrency($item_array['precoTotal']);
                    }
                }

                $itens_array = array_values($itens_array);
                return ['pk_venda' => $venda->pk_venda, 'valor_total' => \Yii::$app->formatter->asCurrency($venda->valor_total), 'valor_pago' => \Yii::$app->formatter->asCurrency($valor_pago), 'itensVenda' => $itens_array];
            } else {
                return ['pk_venda' => -1, 'status' => "É preciso ter uma comanda aberta. Dirija-se ao caixa."];
            }
        }
    }

    /**
     * Retorna as comandas abertas dos clientes no momento
     * @param type $codigo_cliente_app
     * @return type
     */
    public function actionAppGetComandasAbertasGarcom() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $itens_array = [];
        $vendas = Venda::find()->joinWith(['cliente', 'comanda'])->where(['estado' => 'aberta'])->andWhere('pk_cliente is not null or pk_comanda is not null or (nome_temp is not null && nome_temp != "")')->orderBy(['cliente.nome' => 'ASC', 'comanda.numero' => 'ASC'])->all();
        if (!empty($vendas)) {

            foreach ($vendas as $venda) {
                $nome = !empty($venda->cliente) ? $venda->cliente->nome : "";
                $comanda = !empty($venda->comanda) ? $venda->comanda->numero : "";
                $itens_array[] = ['nomeCliente' => $nome, 'nomeTemp' => $venda->nome_temp, 'numeroComanda' => $comanda, 'pkVenda' => $venda->pk_venda];
            }
        }
        return $itens_array;
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

    public function actionAppVerificarStatusPedido($pk_pedido_app) {
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
        $itens_pedido = &$_POST['ItemPedido'];
        $pedido = PedidoApp::findOne($id_pedido);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $salvoTodos = true;


        if (!empty($pedido) && (!empty($itens_pedido))) {
            $venda = Venda::findOne($id_venda);
            if (!empty($venda)) {
                foreach ($itens_pedido as $index => $item_pedido) {
                    $pedidoAux = ItemPedidoApp::find()->where('pk_item_pedido_app = :pk_item_pedido_app', [':pk_item_pedido_app' => $index])->one();
                    if (!empty($pedidoAux)) {
                        $item_venda = new ItemVenda();
                        $item_venda->fk_venda = $venda->pk_venda;
                        $item_venda->fk_preco = $pedidoAux->fk_preco;
                        $item_venda->quantidade = $pedidoAux->quantidade;
                        $item_venda->preco_unitario = $pedidoAux->preco->preco;
                        $item_venda->is_venda_app = 1;

                        $salvoTodos = $salvoTodos && $item_venda->save();
                    }
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

       public function actionAppRequisitaPedidosComandaAbertaGarcom($pkVenda) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($pkVenda)) {
            $pkVenda = $this->decriptografar($pkVenda);

            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->where(['pk_venda' => $pkVenda, 'estado' => 'aberta'])->one();

            if (!empty($venda)) {
                $pedidos = $venda->pedidosApp;
                if (!empty($pedidos)) {
                    $retorno = [];
                    foreach ($pedidos as $pedido) {
                        $itens = $pedido->itensPedidoApp;
                        $itens_array = [];
                        foreach ($itens as $item) {
                            $aux['fk_preco'] = $item->fk_preco;
                            $aux['denominacao'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $aux['quantidade'] = $item->quantidade;
                            $aux['preco'] = $item->preco->preco;
                            $aux['precoTotal'] = \Yii::$app->formatter->asCurrency($item->preco->preco * $item->quantidade);

                            $itens_array[] = $aux;
                        }

                        $retorno[] = ['status' => $pedido->status,
                            'observacoes' => $pedido->observacoes,
                            'pk_pedido_app' => $pedido->pk_pedido_app,
                            'dt_pedido' => Yii::$app->formatter->asDateTime($pedido->dt_pedido),
                            'itens' => $itens_array
                        ];
                    }
                    return $retorno;
                }
            } else {
                return [['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."]];
            }
        }
    }
    
    public function actionAppRequisitaPedidosComandaAberta($codigo_cliente_app) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!empty($codigo_cliente_app)) {
            $codigo_cliente_app = $this->decriptografar($codigo_cliente_app);

            //verifica se o cliente tem uma comanda aberta
            $venda = Venda::find()->joinWith('cliente')->where(['codigo_cliente_app' => $codigo_cliente_app, 'estado' => 'aberta'])->one();

            if (!empty($venda)) {
                $pedidos = $venda->pedidosApp;
                if (!empty($pedidos)) {
                    $retorno = [];
                    foreach ($pedidos as $pedido) {
                        $itens = $pedido->itensPedidoApp;
                        $itens_array = [];
                        foreach ($itens as $item) {
                            $aux['fk_preco'] = $item->fk_preco;
                            $aux['denominacao'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                            $aux['quantidade'] = $item->quantidade;
                            $aux['preco'] = $item->preco->preco;
                            $aux['precoTotal'] = \Yii::$app->formatter->asCurrency($item->preco->preco * $item->quantidade);

                            $itens_array[] = $aux;
                        }

                        $retorno[] = ['status' => $pedido->status,
                            'pk_pedido_app' => $pedido->pk_pedido_app,
                            'observacoes' => $pedido->observacoes,
                            'dt_pedido' => Yii::$app->formatter->asDateTime($pedido->dt_pedido),
                            'itens' => $itens_array
                        ];
                    }
                    return $retorno;
                }
            } else {
                return [['pk_pedido_app' => -1, 'status' => "É preciso ter uma comanda aberta para iniciar os pedidos. Dirija-se ao caixa."]];
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
