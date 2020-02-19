<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uf".
 *
 * @property string $cod_uf
 * @property string $nome_uf
 * @property string $sigla_uf
 */
class Uf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uf';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cod_uf', 'nome_uf', 'sigla_uf'], 'required'],
            [['cod_uf', 'sigla_uf'], 'string', 'max' => 2],
            [['nome_uf'], 'string', 'max' => 45],
            [['cod_uf'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cod_uf' => 'Cod Uf',
            'nome_uf' => 'Nome Uf',
            'sigla_uf' => 'Sigla Uf',
        ];
    }
}
