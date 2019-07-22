<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $pk_cliente
 * @property string $nome
 * @property string $telefone
 * @property string $cpf
 * @property string $dt_nascimento
 *
 * @property Venda[] $vendas
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
   
            [['pk_cliente'], 'integer'],
            [['dt_nascimento'], 'safe'],
            [['nome'], 'string', 'max' => 200],
            [['telefone'], 'string', 'max' => 10],
            [['cpf'], 'string', 'max' => 14],
            [['pk_cliente'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_cliente' => 'Pk Cliente',
            'nome' => 'Nome',
            'telefone' => 'Telefone',
            'cpf' => 'Cpf',
            'dt_nascimento' => 'Dt Nascimento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendas()
    {
        return $this->hasMany(Venda::className(), ['fk_cliente' => 'pk_cliente']);
    }
}
