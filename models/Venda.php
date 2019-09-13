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
            [['valor_total', 'desconto', 'valor_final', 'valor_pago_debito', 'valor_pago_credito', 'valor_pago_dinheiro'], 'number', 'min' => 0],
            [['valor_total', 'desconto', 'valor_final', 'valor_pago_debito', 'valor_pago_credito', 'valor_pago_dinheiro'], 'default', 'value' => 0],
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
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItensVenda() {
        return $this->hasMany(ItemVenda::className(), ['fk_venda' => 'pk_venda']);
    }

    public function atualizaValorFinal() {
        $precos = $this->itensVenda;
        $total = 0;
        if (!empty($precos)) {
            foreach ($precos as $preco) {
                $total = $total + $preco->preco_final;
            }
        }

        $this->valor_total = $total;
        $this->valor_final = $total - $this->desconto;
        $this->save();
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
    public function getData_Venda_Formato_Linha(){
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
    public function getFkUsuarioRecebeuPagamento() {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario_recebeu_pagamento']);
    }

    public function beforeSave($insert) {
        $this->fk_usuario_iniciou_venda = 'dforlani';
        $this->fk_usuario_recebeu_pagamento = 'dforlani';

        $this->valor_final = $this->valor_total - $this->desconto;

        return parent::beforeSave($insert);
    }

    public function getTroco() {
        //só vai ter troco se algo foi pago
        if ($this->valor_pago_credito + $this->valor_pago_dinheiro + $this->valor_pago_debito > 0)
            return Yii::$app->formatter->asCurrency(($this->desconto + $this->valor_pago_credito + $this->valor_pago_dinheiro + $this->valor_pago_debito) - $this->valor_total);
        else
            return Yii::$app->formatter->asCurrency(0);
    }

    public function getValorTotalPago() {
        return Yii::$app->formatter->asCurrency($this->valor_pago_credito + $this->valor_pago_dinheiro + $this->valor_pago_debito);
    }

}
