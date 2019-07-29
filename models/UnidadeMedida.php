<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unidade_medida".
 *
 * @property string $unidade_medida
 * @property int $pk_unidade_medida
 *
 * @property Produto[] $produtos
 */
class UnidadeMedida extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unidade_medida';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unidade_medida'], 'required'],
            [['unidade_medida'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'unidade_medida' => 'Unidade Medida',
            'pk_unidade_medida' => 'Pk Unidade Medida',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produto::className(), ['fk_unidade_medida' => 'pk_unidade_medida']);
    }
}
