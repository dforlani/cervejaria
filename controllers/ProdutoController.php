<?php

namespace app\controllers;

use app\models\Configuracao;
use app\models\Entrada;
use app\models\EntradaSearch;
use app\models\Preco;
use app\models\PrecoSearch;
use app\models\Produto;
use app\models\ProdutoSearch;
use DateTime;
use kartik\grid\EditableColumnAction;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
        ];
    }

    public function actions() {
        return ArrayHelper::merge(parent::actions(), [
//Função pra salvar a edição em EditColumn da GRID
                    'edit-forma-venda-ativa' => [// identifier for your editable column action
                        'class' => EditableColumnAction::className(), // action class name
                        'modelClass' => Preco::className(), // the model for the record being edited
                        'outputValue' => function ($model, $attribute, $key, $index) {
                            return $model->$attribute ? "Sim" : "Não";      // return any custom output value if desired
                        },
                        'outputMessage' => function($model, $attribute, $key, $index) {
                            return '';                                  // any custom error to return after model save
                        },
                        'showModelErrors' => true, // show model validation errors after save
                        'errorOptions' => ['header' => '']               // error summary HTML options
                    ],
        ]);
    }

    public function actionEditEntrada($id) {
        $model = $this->findModelEntrada($id);
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model->load(Yii::$app->request->post());


        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asDecimal($model->quantidade, 3), 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Não salvou: ' . implode(',', $model->getErrorSummary(true))];
        }
    }

    /**
     * Lists all Produto models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProdutoSearch();
//        $_GET['ProdutoSearch']['tipo_produto'] = ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Produto::getTiposProdutosNaoCerveja());


        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Produto models.
     * @return mixed
     */
    public function actionCerveja() {
        $searchModel = new ProdutoSearch();
//        $_GET['ProdutoSearch']['tipo_produto'] = ;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, [Produto::$TIPO_CERVEJA]);

        return $this->render('cerveja/index', [
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

    /**
     * Altera o preço utilizando o componente do kartik Editable
     * @param type $id
     * @return type
     */
    public function actionAlteraPreco($id) {
        $model = $this->findModelPreco($id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_GET['grid'])) {//temos duas telas que enviam de formas diferentes a requisição          
            $preco['Preco']['preco'] = $_POST['preco'];
            $model->load($preco);
        } else {
            $model->load(Yii::$app->request->post());
        }

        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asCurrency($model->preco), 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Zicou e não salvou'];
        }
    }

    public function actionAlteraFormaVendaAtiva($id) {
        $model = $this->findModelPreco($id);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_POST['ativo'])) {//temos duas telas que enviam de formas diferentes a requisição          
            $preco['Preco']['ativo'] = $_POST['ativo'];

            $model->load($preco);
        } else {
            $model->load(Yii::$app->request->post());
        }

        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asBoolean($model->ativo), 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Zicou e não salvou'];
        }
    }

    /**
     * Altera se a promoção está ativa usando o componete Editable do kartik
     * @param type $id
     * @return type
     */
    public function actionAlteraPromocaoAtiva($id) {
        $model = $this->findModelPreco($id);
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (isset($_GET['grid'])) {//temos duas telas que enviam de formas diferentes a requisição          
            $is_promocao_ativa['Preco']['is_promocao_ativa'] = Yii::$app->request->post('is_promocao_ativa', 0);

            $model->load($is_promocao_ativa);
        } else {
            $model->load(Yii::$app->request->post());
        }

        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asBoolean($model->is_promocao_ativa), 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Não salvou: ' . implode(',', $model->getErrorSummary(true))];
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
        $model->tipo_produto = Produto::$TIPO_OUTRO;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $entrada = new Entrada($model->pk_produto, $model->estoque_inicial, $model->custo_fabricacao, $model->dt_fabricacao, $model->dt_vencimento, $model->nr_lote);
            $entrada->save();

            Yii::$app->session->setFlash('warning', "Produto inserido, cadastre as formas de venda.");
            $this->redirect(['update', 'id' => $model->pk_produto]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionCreateCerveja() {
        $model = new Produto();
        $model->is_vendavel = true;
        $model->tipo_produto = Produto::$TIPO_CERVEJA;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('warning', "Cerveja inserida, cadastre as formas de venda.");

            $entrada = new Entrada($model->pk_produto, $model->estoque_inicial, $model->custo_fabricacao, $model->dt_fabricacao, $model->dt_vencimento, $model->nr_lote);
            $entrada->save();

            $this->redirect(['update-cerveja', 'id' => $model->pk_produto]);
        }

        return $this->render('cerveja/create', [
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

        $searchModelEntrada = new EntradaSearch();
        $dataProviderEntrada = $searchModelEntrada->search(['EntradaSearch' => ['fk_produto' => $model->pk_produto]]);


        return $this->render('update', [
                    'model' => $model,
                    'searchModelPreco' => $searchModelPreco,
                    'dataProviderPreco' => $dataProviderPreco,
                    'searchModelEntrada' => $searchModelEntrada,
                    'dataProviderEntrada' => $dataProviderEntrada,
                    'url_retorno' => 'produto',
                    'title' => 'Produto'
        ]);
    }

    public function actionAlteraProdutoIsVendavel($id) {
        $model = $this->findModel($id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $index = array_shift($_POST['Produto']);
        $model->is_vendavel = $index['is_vendavel'];

        if ($model->save()) {
            return ['output' => Yii::$app->formatter->asBoolean($model->is_vendavel), 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Não salvou: ' . implode(',', $model->getErrorSummary(true))];
        }
    }

    public function actionAlteraProdutoTipo($id) {
        $model = $this->findModel($id);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $index = array_shift($_POST['Produto']);
        $model->tipo_produto = $index['tipo_produto'];

        if ($model->save()) {
            return ['output' => $model->tipo_produto, 'message' => ''];
        } else {
            return ['output' => '', 'message' => 'Não salvou: ' . implode(',', $model->getErrorSummary(true))];
        }
    }

    public function actionUpdateCerveja($id) {

        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cerveja']);
        }

        $searchModelPreco = new PrecoSearch();
        $dataProviderPreco = $searchModelPreco->search(['PrecoSearch' => ['fk_produto' => $model->pk_produto]]);

        $searchModelEntrada = new EntradaSearch();
        $dataProviderEntrada = $searchModelEntrada->search(['EntradaSearch' => ['fk_produto' => $model->pk_produto]]);

        return $this->render('update', [
                    'model' => $model,
                    'searchModelPreco' => $searchModelPreco,
                    'dataProviderPreco' => $dataProviderPreco,
                    'searchModelEntrada' => $searchModelEntrada,
                    'dataProviderEntrada' => $dataProviderEntrada,
                    'url_retorno' => 'index',
                    'title' => 'Cerveja'
        ]);
    }

    /**
     * Permite a inserção de produtos na Tap List
     * @return type
     */
    public function actionTapList() {

        if (!empty($_POST['Preco'])) {
            //vindo de um pedido de inclusão na tap list
            if (isset($_POST['Preco']['pk_preco'])) {
                $model = $this->findModelPreco($_POST['Preco']['pk_preco']);

                //se já estiver na tap list, não vai adicionar novamente
                if (!$model->isTapList() && !$model->isCardapioApp()) {
                    $pos = Preco::getMaiorPosicaoTipoCardapio(Preco::$TIPO_CARDAPIO_TAP_LIST);
                    if (empty($pos))
                        $pos = 0;

                    $model->tipo_cardapio = Preco::$TIPO_CARDAPIO_TAP_LIST;
                    $model->pos_cardapio = $pos + 1;
                }
            } else
            if (isset($_POST['editableKey'])) {
                $model = $this->findModelPreco($_POST['editableKey']);

                $model->pos_cardapio = $_POST['Preco'][$_POST['editableIndex']]['pos_cardapio'];
                $model->save();
                $mensagem = '';

                if (!empty($model->getErrors())) {
                    $mensagem = implode(', ', $model->getErrorSummary(true));
                }

                echo Json::encode(['output' => $model->pos_cardapio, 'message' => $mensagem]);

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
            $_GET['sort'] = 'pos_cardapio';

        $dataProvider = $searchModel->search(['PrecoSearch' => ['tipo_cardapio' => Preco::$TIPO_CARDAPIO_TAP_LIST],]);


        return $this->render('tap_list', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Permite a inserção de produtos no Cardápio para aparecerem no App
     * @return type
     */
    public function actionCardapioApp() {

        if (!empty($_POST['Preco'])) {
            //vindo de um pedido de inclusão na tap list
            if (isset($_POST['Preco']['pk_preco'])) {
                $model = $this->findModelPreco($_POST['Preco']['pk_preco']);

                //se já estiver na tap list, não vai adicionar novamente
                if (!$model->isTapList() && !$model->isCardapioApp()) {
                    $pos = Preco::getMaiorPosicaoTipoCardapio(Preco::$TIPO_CARDAPIO_CARDAPIO);

                    $model->tipo_cardapio = Preco::$TIPO_CARDAPIO_CARDAPIO;
                    $model->pos_cardapio = $pos + 1;
                }
            } elseif (isset($_POST['editableKey'])) {
                $model = $this->findModelPreco($_POST['editableKey']);

                $model->pos_cardapio = $_POST['Preco'][$_POST['editableIndex']]['pos_cardapio'];
                $model->save();
                $mensagem = '';

                if (!empty($model->getErrors())) {
                    $mensagem = implode(', ', $model->getErrorSummary(true));
                }

                echo Json::encode(['output' => $model->pos_cardapio, 'message' => $mensagem]);

                return;
            }
        } else {
            $model = new Preco();
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['cardapio-app']);
        }



        $searchModel = new PrecoSearch();
        if (empty($_GET['sort']))
            $_GET['sort'] = 'pos_cardapio';

        $dataProvider = $searchModel->search(['PrecoSearch' => ['tipo_cardapio' => Preco::$TIPO_CARDAPIO_CARDAPIO],]);


        return $this->render('cardapio_app', [
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

    public function actionCreateEntrada($pk_produto) {
        $model = new Entrada();

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
            return $this->renderAjax('entrada/create', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('entrada/create', [
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
                } else {//se não for requisição ajax, volta direto para a listagem de produtos
                    return $this->redirect(['/produto']);
                    //return $this->redirect(['update', 'id' => $model->fk_produto]);
                }
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

    public function actionUpdateEntrada($pk_entrada = null) {

        $model = $this->findModelEntrada($pk_entrada);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                if (Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true];
                } else {//se não for requisição ajax, volta direto para a listagem de produtos
                    return $this->redirect(['/produto']);
                    //return $this->redirect(['update', 'id' => $model->fk_produto]);
                }
            }
        }


        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('entrada/update', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('entrada/update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDeletePreco($pk_preco) {
        $model = $this->findModelPreco($pk_preco);
        $pk_produto = $model->fk_produto;
        $is_cerveja = $model->produto->isCerveja();
        $model->delete();

        if ($is_cerveja)
            return $this->redirect(['update-cerveja', 'id' => $pk_produto]);
        else
            return $this->redirect(['update', 'id' => $pk_produto]);
    }

    public function actionDeleteEntrada($pk_entrada) {
        $model = $this->findModelEntrada($pk_entrada);
        $pk_produto = $model->fk_produto;
        $is_cerveja = $model->produto->isCerveja();
        $model->delete();

        if ($is_cerveja)
            return $this->redirect(['update-cerveja', 'id' => $pk_produto]);
        else
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
        $model = $this->findModel($id);
        $is_cerveja = $model->isCerveja();
        $this->findModel($id)->delete();

        if ($is_cerveja) {
            return $this->redirect(['cerveja']);
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionRemoverTapList($id) {
        $this->removerTipoCardapio($id);

        return $this->redirect(['tap-list']);
    }

    public function actionRemoverCardapio($id) {

        $this->removerTipoCardapio($id);
        return $this->redirect(['cardapio-app']);
    }

    public function removerTipoCardapio($id) {
        $model = $this->findModelPreco($id);
        $model->tipo_cardapio = Preco::$TIPO_CARDAPIO_NENHUM;
        $model->pos_cardapio = null;
        $model->save();
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

    protected function findModelEntrada($id) {
        if (($model = Entrada::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCardapioAutomatico() {
        $conf_aux = Configuracao::getConfiguracaoByTipo("tempo_em_minutos_para_cardapio_automatico");
        if ($conf_aux->valor != 0) {//se o valor é igual a zero, não gera o backup
            $minutos_minimo_pro_cardapio = $conf_aux->valor;
            $conf_aux = Configuracao::getConfiguracaoByTipo("dia_e_hora_desde_ultimo_cardapio_automatico");
            $ultimo_cardapio = new DateTime($conf_aux->valor);
            $agora = new DateTime("now");

            $interval = $agora->diff($ultimo_cardapio);
            $minutos_passado = $interval->format('%i');


            if (true || $minutos_passado >= $minutos_minimo_pro_cardapio) {

                $drinks = Produto::find()->where(['is_vendavel' => true, 'tipo_produto' => Produto::$TIPO_DRINK])->orderBy('nome')->all();
                $bebidas = Produto::find()->where(['is_vendavel' => true, 'tipo_produto' => Produto::$TIPO_BEBIDA])->orderBy('nome')->all();
                $cervejas = Produto::find()->where(['is_vendavel' => true, 'tipo_produto' => Produto::$TIPO_CERVEJA])->orderBy('nome')->all();
                $aperitivos = Produto::find()->where(['is_vendavel' => true, 'tipo_produto' => Produto::$TIPO_APERITIVO])->orderBy('nome')->all();

                $content = $this->renderPartial('gera_cardapio', [
                    'drinks' => $drinks,
                    'bebidas' => $bebidas,
                    'cervejas' => $cervejas,
                    'aperitivos' => $aperitivos
                ]);
                $text = new \Mpdf\Mpdf();
                ob_end_clean();

                $mpdf = new \Mpdf\Mpdf();
                //$mpdf->showImageErrors = true;
//                $mpdf->debug = true;
                $mpdf->WriteHTML($content);
                $mpdf->Output('../pdf/cardapio/cardapio.pdf', 'F');
                exit;
                
            }
            echo $minutos_passado;
        }
    }

}
