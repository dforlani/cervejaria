<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_pedido_app".
 *
 * @property int $pk_item_pedido_app
 * @property int $fk_pedido_app
 * @property int $fk_preco
 * @property int $quantidade
 *
 * @property PedidoApp $fkPedidoApp
 * @property Preco $fkPreco
 */
class ItemPedidoApp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_pedido_app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_pedido_app', 'fk_preco', 'quantidade'], 'required'],
            [['fk_pedido_app', 'fk_preco', 'quantidade'], 'integer'],
            [['fk_pedido_app'], 'exist', 'skipOnError' => true, 'targetClass' => PedidoApp::className(), 'targetAttribute' => ['fk_pedido_app' => 'pk_pedido_app']],
            [['fk_preco'], 'exist', 'skipOnError' => true, 'targetClass' => Preco::className(), 'targetAttribute' => ['fk_preco' => 'pk_preco']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_item_pedido_app' => 'Pk Item Pedido App',
            'fk_pedido_app' => 'Fk Pedido App',
            'fk_preco' => 'Fk Preco',
            'quantidade' => 'Quantidade',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPedidoApp()
    {
        return $this->hasOne(PedidoApp::className(), ['pk_pedido_app' => 'fk_pedido_app']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreco()
    {
        return $this->hasOne(Preco::className(), ['pk_preco' => 'fk_preco']);
    }
}
