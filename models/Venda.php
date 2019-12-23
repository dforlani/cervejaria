<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "venda".
 *
 * @property int $pk_venda
 * @property int $fk_cliente
 * @property int $fk_comanda
 * @property string $fk_usuario_iniciou_venda
 * @property string $fk_usuario_recebeu_pagamento
 * @property string $valor_total
 * @property string $troco
 * @property string $desconto
 * @property string $valor_final
 * @property string $valor_pago_credito
 * @property string $valor_pago_debito
 * @property string $valor_pago_dinheiro
 * @property string $estado
 * @property string $dt_venda
 * @property string $dt_pagamento
 *
 * @property ItemVenda[] $itemVendas
 * @property Cliente $fkCliente
 * @property Comanda $fkComanda
 * @property Usuario $fkUsuarioIniciouVenda
 * @property Usuario $fkUsuarioRecebeuPagamento
 */
class Venda extends ActiveRecord {

    public $pagamentos;
    public $pagamentos_liquido;
    public $quantidade;
    public $unidade_medida;
    public $nome_cliente;
    public $produto;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'venda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_venda', 'fk_cliente', 'fk_comanda'], 'integer'],
            [['valor_total', 'desconto', 'valor_final', 'valor_pago_debito', 'valor_pago_credito', 'valor_pago_dinheiro',], 'number', 'min' => 0],
            [['troco'], 'number', 'max' => 0],
            [['valor_total', 'desconto', 'valor_final', 'valor_pago_debito', 'valor_pago_credito', 'valor_pago_dinheiro', 'troco'], 'default', 'value' => 0],
            [['estado'], 'string'],
            [['dt_venda', 'dt_pagamento'], 'safe'],
            [['fk_usuario_iniciou_venda', 'fk_usuario_recebeu_pagamento'], 'string', 'max' => 20],
            [['pk_venda'], 'unique'],
            [['estado'], 'default', 'value' => 'aberta'],
            [['fk_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['fk_cliente' => 'pk_cliente']],
            [['fk_comanda'], 'exist', 'skipOnError' => true, 'targetClass' => Comanda::className(), 'targetAttribute' => ['fk_comanda' => 'pk_comanda']],
            [['fk_usuario_iniciou_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario_iniciou_venda' => 'login']],
            [['fk_usuario_recebeu_pagamento'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario_recebeu_pagamento' => 'login']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_venda' => 'Pk Venda',
            'fk_cliente' => 'Fk Cliente',
            'fk_comanda' => 'Fk Comanda',
            'fk_usuario_iniciou_venda' => 'Fk Usuario Iniciou Venda',
            'fk_usuario_recebeu_pagamento' => 'Fk Usuario Recebeu Pagamento',
            'valor_total' => 'Valor Total',
            'desconto' => 'Desconto',
            'valor_final' => 'Valor Final',
            'valor_pago_debito' => 'Pago em Débito',
            'valor_pago_credito' => 'Pago em Crédito',
            'valor_pago_dinheiro' => 'Pago em Dinheiro',
            'estado' => 'Estado',
            'dt_venda' => 'Data Venda',
            'dt_pagamento' => 'Data Pagamento',
            'itens_sem_preco_custo' => 'Itens Sem Preço de Custo'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItensVenda() {
        return $this->hasMany(ItemVenda::className(), ['fk_venda' => 'pk_venda']);
    }
	
	   /**
     * @return ActiveQuery
     */
    public function getPedidosApp() {
        return $this->hasMany(PedidoApp::className(), ['fk_venda' => 'pk_venda'])->orderBy('dt_pedido DESC');
    }

    public function isPaga() {
        return $this->estado == 'paga';
    }

    public function atualizaValorFinal() {       

        //fiz a consulta pq se buscar usando o alias ->itensVenda, não retornava todos os descontos, por estes terem sido buscados anteriormente
        //e a atualização ter sido feita depois (Lazy Loading)
        $precos = ItemVenda::findAll(['fk_venda'=>$this->pk_venda]);
        $total = 0;
        if (!empty($precos)) {
            foreach ($precos as $preco) {
                if (is_numeric($preco->preco_final))
                    $total = $total + $preco->preco_final;
            }
        }

        $this->valor_total = $total;
        $desconto = is_numeric($this->desconto) ? $this->desconto : 0;

        $this->valor_final = $total - $desconto;

        $this->troco = $this->getTroco();
    }

    /**
     * Inclui descontos para itens em promoção que atingiram um valor indicado
     */
    public function verificaItensEmPromocao() {
        $qtdItensPromocao = [];

        foreach ($this->itensVenda as $item) {
            //remove todos pra depois inserir novamente
            if ($item->is_desconto_promocional) {
                $item->delete();
            } else {
                
                if ($item->preco->is_promocao_ativa) {
                    if (!isset($qtdItensPromocao[$item->preco->pk_preco])) {
                        $qtdItensPromocao[$item->preco->pk_preco]['qtd_atingida'] = $item->quantidade;
                        $qtdItensPromocao[$item->preco->pk_preco]['model'] = $item;
                    } else {
                        $qtdItensPromocao[$item->preco->pk_preco]['qtd_atingida'] = $qtdItensPromocao[$item->preco->pk_preco]['qtd_atingida'] +  $item->quantidade;
                    }
                }
            }
        }

        foreach ($qtdItensPromocao as $pk_preco => $itemPromocao) {
            //echo $itemPromocao['qtd_atingida'] . '<br>';
            //echo $itemPromocao['model']->preco->promocao_quantidade_atingir . '<br>';
            //echo (int) ($itemPromocao['qtd_atingida'] / $itemPromocao['model']->preco->promocao_quantidade_atingir);
            //exit();
            $qtd_promocao = (int) ($itemPromocao['qtd_atingida'] / $itemPromocao['model']->preco->promocao_quantidade_atingir);
            if($qtd_promocao > 0){
                $itemVenda = new ItemVenda();
                $itemVenda->fk_preco = $pk_preco;
                $itemVenda->fk_venda = $this->pk_venda;
                $itemVenda->quantidade = $qtd_promocao;
                $itemVenda->preco_unitario = 0;
                $itemVenda->is_desconto_promocional = true;
                $itemVenda->preco_final = $qtd_promocao  * $itemPromocao['model']->preco->promocao_desconto_aplicar;
                $itemVenda->preco_unitario = $itemPromocao['model']->preco->promocao_desconto_aplicar;
                if (!$itemVenda->save()) {
                    exit();
                }
            }
        }
    }

    public static function getArrayVendasEmAberto() {
        $vendas = Venda::findByCondition(['estado' => 'aberta'])->orderBy('nome')->joinWith('cliente')->all();
        $lista = [];
        if (!empty($vendas))
            foreach ($vendas as $venda) {
                $lista[$venda->pk_venda] = (!empty($venda->cliente) ? '' . $venda->cliente->nome : '') . ' => ' . (!empty($venda->comanda) ? $venda->comanda->getComandaComDigitoVerificador() : '');
            }
        return $lista;
    }

    /**
     * 
     * @return type
     */
    public function getData_Venda_Formato_Linha() {
        return Yii::$app->formatter->asDate($this->dt_venda, 'd-m-Y_h-m');
    }

    /**
     * @return ActiveQuery
     */
    public function getCliente() {

        return $this->hasOne(Cliente::className(), ['pk_cliente' => 'fk_cliente']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComanda() {
        return $this->hasOne(Comanda::className(), ['pk_comanda' => 'fk_comanda']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFkUsuarioIniciouVenda() {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario_iniciou_venda']);
    }

    /**
     * @return ActiveQuery
     */
    public function getItemCaixa() {
        return $this->hasOne(ItemCaixa::className(), ['fk_venda' => 'pk_venda']);
    }

    /**
     * @return ActiveQuery
     */
    public function getFkUsuarioRecebeuPagamento() {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario_recebeu_pagamento']);
    }

    public function beforeValidate() {
        $this->verificaItensEmPromocao();
        $this->atualizaValorFinal();
        
        if($this->isPaga()){
            $this->dt_pagamento =  new \yii\db\Expression('NOW()');
        }

        return parent::beforeValidate();
    }

    public function beforeSave($insert) {
        $this->fk_usuario_iniciou_venda = 'dforlani';
        $this->fk_usuario_recebeu_pagamento = 'dforlani';



        //inclui ou atualiza um item no caixa
        if ($this->isPaga()) {
            $item_caixa = $this->itemCaixa;
            if (empty($item_caixa)) {
                $caixa = Caixa::getCaixaAberto();
                if (!empty($caixa)) {
                    $item_caixa = new ItemCaixa(['fk_venda' => $this->pk_venda, 'fk_caixa' => $caixa->pk_caixa]);
                } else {
                    echo 'Errrrrrrrroooooooo: Não foi encontrado nenhum caixa aberto';
                    exit();
                }
            }

            $item_caixa->valor_credito = $this->valor_pago_credito;
            $item_caixa->valor_debito = $this->valor_pago_debito;
            $item_caixa->valor_dinheiro = $this->valor_pago_dinheiro + $this->troco;

            $item_caixa->tipo = 'Entrada - Recebimento de Pagamento';
            $item_caixa->save();
        } else {
            //se tiver algo no caixa, é pq trocou o estado, daí remove o item em caixa
            $item_caixa = $this->itemCaixa;
            if (!empty($item_caixa)) {
                $item_caixa->delete();
            }
        }


        return parent::beforeSave($insert);
    }

    public function getTroco() {

        $saldo = $this->getSaldo();
        if ($saldo <= 0) {
            return $saldo;
        } else {//está faltando
            return 0;
        }
    }

    public function getSaldo() {
        $valor_total = is_numeric($this->valor_total) ? $this->valor_total : 0;
        $valor_pago_credito = is_numeric($this->valor_pago_credito) ? $this->valor_pago_credito : 0;
        $valor_pago_dinheiro = is_numeric($this->valor_pago_dinheiro) ? $this->valor_pago_dinheiro : 0;
        $valor_pago_debito = is_numeric($this->valor_pago_debito) ? $this->valor_pago_debito : 0;
        $desconto = is_numeric($this->desconto) ? $this->desconto : 0;

        return $valor_total - ($valor_pago_credito + $valor_pago_dinheiro + $valor_pago_debito + $desconto);
    }

    public function getSaldoFormatedBR() {
        //só vai ter troco se algo foi pago
        $saldo = $this->getSaldo();
        return Yii::$app->formatter->asCurrency($saldo);
    }

    public function hasTroco() {
        return $this->getSaldo() < 0;
    }

    public function hasFalta() {
        return $this->getSaldo() >= 0;
    }

    public function getValorTotalPago() {
        return Yii::$app->formatter->asCurrency($this->valor_pago_credito + $this->valor_pago_dinheiro + $this->valor_pago_debito);
    }

}
