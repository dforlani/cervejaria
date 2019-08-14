<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comanda".
 *
 * @property int $pk_comanda
 * @property int $numero
 *
 * @property Venda[] $vendas
 */
class Comanda extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comanda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
            [['pk_comanda', 'numero'], 'integer'],
            [['numero'], 'unique'],
            [['pk_comanda'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_comanda' => 'Pk Comanda',
            'numero' => 'Código de Barras',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendas()
    {
        return $this->hasMany(Venda::className(), ['fk_comanda' => 'pk_comanda']);
    }
    
 public static function getComandasSemVendasAbertas(){
        return Comanda::findBySql('select * from comanda where pk_comanda not in (select fk_comanda from venda where estado = "aberta" and fk_comanda is not null)')->all();
    }
}
