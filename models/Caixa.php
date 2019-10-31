<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caixa".
 *
 * @property int $pk_caixa
 * @property string $dt_abertura
 * @property string $dt_fechamento
 * @property string $estado
 */
class Caixa extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'caixa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['dt_abertura', 'dt_fechamento'], 'safe'],
            [['estado'], 'required'],
            [['estado'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_caixa' => 'Pk Caixa',
            'dt_abertura' => 'Abertura',
            'dt_fechamento' => 'Fechamento',
            'estado' => 'Estado',
            'valorSaidas' =>'Saídas',
            'valorEntradas'=>'Entradas'
        ];
    }

    /**
     * Retorna um registro de caixa aberto
     * Deverá existir apenas um caixa aberto por vez
     */
    public static function getCaixaAberto() {
        return Caixa::findOne(['estado' => 'aberto']);
    }

    /**
     * Retorn true se tiver um caixa aberto e false se não
     */
    public static function hasCaixaAberto() {
        return !empty(Caixa::getCaixaAberto());
    }

    public function getItensCaixa() {
        return $this->hasMany(ItemCaixa::className(), ['fk_caixa' => 'pk_caixa']);
    }

    public function getValorEntradas() {
        $movimentos = $this->itensCaixa;
        $entradas = 0;
        if (!empty($movimentos)) {

            foreach ($movimentos as $movimento) {
                if($movimento->isEntrada()){
                    $entradas += $movimento->valor_dinheiro + $movimento->valor_debito + $movimento->valor_credito;
                }
            }
        }
        return $entradas;
    }
    
     public function getValorSaidas() {
        $movimentos = $this->itensCaixa;
        $entradas = 0;
        if (!empty($movimentos)) {

            foreach ($movimentos as $movimento) {
                if($movimento->isPgtoDespesa()){
                    $entradas += $movimento->valor_dinheiro;
                }
            }
        }
        return $entradas;
    }

}
