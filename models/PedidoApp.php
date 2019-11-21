<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido_app".
 *
 * @property int $pk_pedido_app
 * @property int $fk_cliente
 * @property string $status
 *
 * @property ItemPedidoApp[] $itemPedidoApps
 * @property Cliente $fkCliente
 */
class PedidoApp extends \yii\db\ActiveRecord{
    public static $CONST_STATUS_ERRO = "Erro";
    public static $CONST_STATUS_EM_ATENDIMENTO = "Em Atendimento";
    public static $CONST_STATUS_ENVIADO = "Enviado";
    public static $CONST_STATUS_PRONTO = "Pronto";
    public static $CONST_STATUS_CANCELADO_PELO_ATENDENTE = "Cancelado pelo Atendente";
    public static $CONST_STATUS_CANCELADO_PELO_CLIENTE = "Cancelado pelo Cliente";
   
    

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_cliente'], 'required'],
            [['fk_cliente'], 'integer'],
            [['status'], 'string'],
            [['fk_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['fk_cliente' => 'pk_cliente']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_pedido_app' => 'Pk Pedido App',
            'fk_cliente' => 'Fk Cliente',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItensPedidoApp()
    {
        return $this->hasMany(ItemPedidoApp::className(), ['fk_pedido_app' => 'pk_pedido_app']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['pk_cliente' => 'fk_cliente']);
    }
}
