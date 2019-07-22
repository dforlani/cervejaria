<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada".
 *
 * @property int $pk_entrada
 * @property string $fk_usuario
 * @property int $fk_produto
 * @property double $quantidade
 * @property string $dt_entrada
 * @property string $custo
 *
 * @property Produto $fkProduto
 * @property Usuario $fkUsuario
 */
class Entrada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pk_entrada', 'fk_usuario'], 'required'],
            [['pk_entrada', 'fk_produto'], 'integer'],
            [['quantidade', 'custo'], 'number'],
            [['dt_entrada'], 'safe'],
            [['fk_usuario'], 'string', 'max' => 20],
            [['pk_entrada'], 'unique'],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
            [['fk_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario' => 'login']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_entrada' => 'Pk Entrada',
            'fk_usuario' => 'Fk Usuario',
            'fk_produto' => 'Fk Produto',
            'quantidade' => 'Quantidade',
            'dt_entrada' => 'Dt Entrada',
            'custo' => 'Custo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkProduto()
    {
        return $this->hasOne(Produto::className(), ['pk_produto' => 'fk_produto']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuario()
    {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario']);
    }
}
