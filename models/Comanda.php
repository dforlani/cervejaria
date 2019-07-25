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
            'numero' => 'Numero',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendas()
    {
        return $this->hasMany(Venda::className(), ['fk_comanda' => 'pk_comanda']);
    }
    
//    /**
//     * So retorna comandas que não estão relacionadas com vendas em aberto
//     */
//    public static function findComandasNaoAbertas(){
//        //return Comanda::find('estado != "aberta"')->all();
//        return Comanda::find()->where('estado != "aberta"')->joinWith('vendas')->orderBy('numero')->all();
//    }
}
