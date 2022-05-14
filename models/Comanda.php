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
class Comanda extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'comanda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_comanda', 'numero'], 'integer'],
            [['numero'], 'unique'],
            [['numero'], 'required'],
            [['pk_comanda'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_comanda' => 'Pk Comanda',
            'numero' => 'Comanda',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendas() {
        return $this->hasMany(Venda::className(), ['fk_comanda' => 'pk_comanda']);
    }

    public static function getComandasSemVendasAbertas() {
        $comandas = Comanda::findBySql('select * from comanda where pk_comanda not in (select fk_comanda from venda where estado = "aberta" and fk_comanda is not null)')->all();
     //   foreach ($comandas as &$comanda){
    //        $comanda->numero = $comanda->getComandaComDigitoVerificador();
            
      //  }
        return $comandas;
    }

    public function beforeSave($insert) {
        $this->numero = $this->getComandaComDigitoVerificador();
        return parent::beforeSave($insert);
    }
    

    public function getComandaComDigitoVerificador() {
        $multiplicador = 1;
        $soma = 0;

        for ($index = 0; $index < strlen($this->numero); $index++) {
            $soma = $soma + substr($this->numero, $index, 1) * $multiplicador;
            $multiplicador = ($multiplicador == 1 ? 3 : 1);
            //echo $soma;
        }

        $resultado = $soma / 10;


        $resultado = floor($resultado);
        $resultado = $resultado + 1;
        $resultado = $resultado * 10;
        $resultado = $resultado - $soma;
        // return $resultado;
        if ($resultado >= 10)
            $resultado = 0;

        return $this->numero . '' . $resultado;
    }

}
