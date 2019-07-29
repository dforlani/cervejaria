<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produto".
 *
 * @property int $pk_produto
 * @property string $nome
 * @property double $estoque
 * @property string $unidade_medida
 *
 * @property Entrada[] $entradas
 * @property Preco[] $precos
 */
class Produto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['pk_produto'], 'required'],
//            [['pk_produto'], 'integer'],
            [['estoque'], 'number'],
            [['nome', 'unidade_medida'], 'string', 'max' => 100],
            [['pk_produto'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_produto' => 'Pk Produto',
            'nome' => 'Nome',
            'estoque' => 'Estoque',
            'unidade_medida' => 'Unidade Medida',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntradas()
    {
        return $this->hasMany(Entrada::className(), ['fk_produto' => 'pk_produto']);
    }
    
    public function getUnidadeMedida()
    {
         return $this->hasOne(UnidadeMedida::className(), ['pk_unidade_medida' => 'fk_unidade_medida']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrecos()
    {
        return $this->hasMany(Preco::className(), ['fk_produto' => 'pk_produto']);
    }
}
