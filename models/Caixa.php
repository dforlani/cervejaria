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
            'dt_abertura' => 'Dt Abertura',
            'dt_fechamento' => 'Dt Fechamento',
            'estado' => 'Estado',
        ];
    }

    /**
     * Retorna um registro de caixa aberto
     * Deverá existir apenas um caixa aberto por vez
     */
    public static function getCaixaAberto() {
        return Caixa::findOne(['estado'=>'aberto']);
        
    }
    
     /**
     * Retorn true se tiver um caixa aberto e false se não
     */
    public static function hasCaixaAberto() {
        return !empty(Caixa::getCaixaAberto());
        
    }

}
