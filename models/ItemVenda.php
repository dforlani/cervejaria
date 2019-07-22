<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_venda".
 *
 * @property int $fk_venda
 * @property int $fk_preco
 * @property double $quantidade
 * @property string $preco_unitario
 * @property string $preco_final
 *
 * @property Venda $fkVenda
 * @property Preco $fkPreco
 */
class ItemVenda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_venda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_venda', 'fk_preco', 'quantidade'], 'required'],
            [['fk_venda', 'fk_preco'], 'integer'],
            [['quantidade', 'preco_unitario', 'preco_final'], 'number'],
            [['fk_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Venda::className(), 'targetAttribute' => ['fk_venda' => 'pk_venda']],
            [['fk_preco'], 'exist', 'skipOnError' => true, 'targetClass' => Preco::className(), 'targetAttribute' => ['fk_preco' => 'pk_preco']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fk_venda' => 'Fk Venda',
            'fk_preco' => 'Fk Preco',
            'quantidade' => 'Quantidade',
            'preco_unitario' => 'Preco Unitario',
            'preco_final' => 'Preco Final',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkVenda()
    {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkPreco()
    {
        return $this->hasOne(Preco::className(), ['pk_preco' => 'fk_preco']);
    }
}
