<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "municipio".
 *
 * @property string $cod_uf
 * @property string $cod_mun
 * @property string $nome_mun
 */
class Municipio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'municipio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cod_uf', 'cod_mun', 'nome_mun'], 'required'],
            [['cod_uf'], 'string', 'max' => 2],
            [['cod_mun'], 'string', 'max' => 7],
            [['nome_mun'], 'string', 'max' => 45],
            [['cod_mun'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cod_uf' => 'UF',
            'cod_mun' => 'Cod Mun',
            'nome_mun' => 'Nome Mun',
        ];
    }
}
