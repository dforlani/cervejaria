<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "caixa".
 *
 * @property int $pk_caixa
 * @property int $fk_venda
 * @property string $valor
 * @property string $tipo
 *
 * @property Venda $fkVenda
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
            [['fk_venda'], 'integer'],
            [['valor'], 'number'],
            [['tipo'], 'string'],
            [['tipo', 'valor'], 'required'],
            [['valor' ], 'validateValor'],
            [['fk_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Venda::className(), 'targetAttribute' => ['fk_venda' => 'pk_venda']],
        ];
    }

    /**
     * Verifica se negativo pra sangria e positivo pra abertura de caixa
     * @param type $attribute
     * @param type $params
     */
    public function validateValor($attribute, $params) {
       if($this->tipo == 'Sangria' && $this->valor > 0){
            $this->addError($attribute, "A Sangria só pode ter valor negativo.");
       }elseif($this->tipo == 'Abertura de Caixa' && $this->valor < 0){
           $this->addError($attribute, "A Abertura de Caixa deve ter valor positivo.");
       }

     
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_caixa' => 'Pk Caixa',
            'fk_venda' => 'Fk Venda',
            'valor' => 'Valor',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkVenda() {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }

}
