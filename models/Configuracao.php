<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracao".
 *
 * @property int $pk_configuracao
 * @property string $tipo
 * @property string $valor
 */
class Configuracao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'valor'], 'required'],
            [['tipo', 'valor'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_configuracao' => 'Pk Configuracao',
            'tipo' => 'Tipo',
            'valor' => 'Valor',
        ];
    }
}
