<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "pedido_app".
 *
 * @property int $pk_pedido_app
 * @property int $fk_cliente
 * @property string $status
 * @property string $dt_pedido
 * 
 *
 * @property ItemPedidoApp[] $itemPedidoApps
 * @property Cliente $fkCliente
 */
class PedidoApp extends ActiveRecord{
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
            'dt_pedido' =>'Data do Pedido'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getItensPedidoApp()
    {
        return $this->hasMany(ItemPedidoApp::className(), ['fk_pedido_app' => 'pk_pedido_app']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['pk_cliente' => 'fk_cliente']);
    }
    
    /**
     * @return ActiveQuery
     */
    public function getVenda()
    {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }
    
    public static function getTiposStatus(){
        return [PedidoApp::$CONST_STATUS_PRONTO=>PedidoApp::$CONST_STATUS_PRONTO, 
             PedidoApp::$CONST_STATUS_EM_ATENDIMENTO=> PedidoApp::$CONST_STATUS_EM_ATENDIMENTO,
              PedidoApp::$CONST_STATUS_ENVIADO=>  PedidoApp::$CONST_STATUS_ENVIADO,
             PedidoApp::$CONST_STATUS_CANCELADO_PELO_CLIENTE=> PedidoApp::$CONST_STATUS_CANCELADO_PELO_CLIENTE,
             PedidoApp::$CONST_STATUS_ERRO=> PedidoApp::$CONST_STATUS_ERRO,
            PedidoApp::$CONST_STATUS_CANCELADO_PELO_ATENDENTE => PedidoApp::$CONST_STATUS_CANCELADO_PELO_ATENDENTE, ];
    }
    
    public function getDtPedidoExploded(){
        return json_encode(['dia'=>date('d',strtotime($this->dt_pedido)), //dia        
        'mes'=>date('n',strtotime($this->dt_pedido)), //dia        
        'ano'=>date('Y',strtotime($this->dt_pedido)), //dia        
        'hora'=>date('H',strtotime($this->dt_pedido)), //dia        
        'minuto'=>date('i',strtotime($this->dt_pedido)), //dia        
        'segundo'=>date('s',strtotime($this->dt_pedido)), 
        'pk_pedido_app'=>$this->pk_pedido_app]); //dia              
    }
}
