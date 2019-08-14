<?php

namespace app\models;

use Yii;

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
class Venda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'venda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
       
            [['pk_venda', 'fk_cliente', 'fk_comanda'], 'integer'],
            [['valor_total', 'desconto', 'valor_final'], 'number'],
            [['estado'], 'string'],
            [['dt_venda', 'dt_pagamento'], 'safe'],
            [['fk_usuario_iniciou_venda', 'fk_usuario_recebeu_pagamento'], 'string', 'max' => 20],
            [['pk_venda'], 'unique'],
            [['estado'], 'default', 'value'=> 'aberta'],
            [['fk_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['fk_cliente' => 'pk_cliente']],
            [['fk_comanda'], 'exist', 'skipOnError' => true, 'targetClass' => Comanda::className(), 'targetAttribute' => ['fk_comanda' => 'pk_comanda']],
            [['fk_usuario_iniciou_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario_iniciou_venda' => 'login']],
            [['fk_usuario_recebeu_pagamento'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario_recebeu_pagamento' => 'login']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_venda' => 'Pk Venda',
            'fk_cliente' => 'Fk Cliente',
            'fk_comanda' => 'Fk Comanda',
            'fk_usuario_iniciou_venda' => 'Fk Usuario Iniciou Venda',
            'fk_usuario_recebeu_pagamento' => 'Fk Usuario Recebeu Pagamento',
            'valor_total' => 'Valor Total',
            'desconto' => 'Desconto',
            'valor_final' => 'Valor Final',
            'estado' => 'Estado',
            'dt_venda' => 'Data Venda',
            'dt_pagamento' => 'Data Pagamento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItensVenda()
    {
        return $this->hasMany(ItemVenda::className(), ['fk_venda' => 'pk_venda']);
    }
    
    public function atualizaValorFinal(){
        $precos = $this->itensVenda;
        $total = 0;
        if(!empty($precos)){
            foreach($precos as $preco){
                $total = $total + $preco->preco_final;
            }
        }        
        
        $this->valor_total = $total;
        $this->valor_final = $total - $this->desconto;
        $this->save();
    }
    
    public static function getArrayVendasEmAberto(){
        $vendas = Venda::findByCondition(['estado'=>'aberta'])->all();;
        $lista = [];
        if(!empty($vendas))
        foreach($vendas as $venda){
            $lista[$venda->pk_venda] = (!empty($venda->cliente)?'Cliente: '.$venda->cliente->nome:'') . ' => Comanda: ' . (!empty($venda->comanda)?$venda->comanda->numero:'') ;
        }
        return $lista;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['pk_cliente' => 'fk_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComanda()
    {
        return $this->hasOne(Comanda::className(), ['pk_comanda' => 'fk_comanda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuarioIniciouVenda()
    {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario_iniciou_venda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuarioRecebeuPagamento()
    {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario_recebeu_pagamento']);
    }
    
    public function beforeSave($insert) {
        $this->fk_usuario_iniciou_venda = 'dforlani';
        $this->fk_usuario_recebeu_pagamento = 'dforlani';
        return parent::beforeSave($insert);
        
    }
}
