<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_caixa".
 *
 * @property int $fk_caixa
 * @property int $pk_item_caixa
 * @property int $fk_venda
 * @property string $valor_dinheiro
 * @property string $valor_debito
 * @property string $valor_credito
 * @property string $tipo
 *
 * @property Venda $fkVenda
 */
class ItemCaixa extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'item_caixa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_venda'], 'integer'],
            [['valor_dinheiro', 'valor_credito', 'valor_debito'], 'number'],
            [['tipo', 'categoria'], 'string'],
            [['observacao'], 'string', 'max' => 900],
            [['tipo', 'fk_caixa'], 'required'],
            [['valor_dinheiro'], 'validateValor'],
            [['fk_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Venda::className(), 'targetAttribute' => ['fk_venda' => 'pk_venda']],
        ];
    }

    /**
     * Verifica se negativo pra sangria e positivo pra abertura de caixa
     * @param type $attribute
     * @param type $params
     */
    public function validateValor($attribute, $params) {
        if ($this->tipo == 'Saída - Pagamento de Despesa' && $this->valor_dinheiro > 0) {
            $this->addError($attribute, "A 'Saída - Pagamento de Despesa' só pode ter valor negativo.");
        } elseif ($this->tipo == 'Sangria' && $this->valor_dinheiro > 0) {
            $this->addError($attribute, "A Sangria só pode ter valor negativo.");
        } elseif ($this->tipo == 'Abertura de Caixa' && $this->valor_dinheiro < 0) {
            $this->addError($attribute, "A Abertura de Caixa deve ter valor positivo.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_item_caixa' => 'Pk Item Caixa',
            'fk_venda' => 'Fk Venda',
            'valor_dinheiro' => 'Dinheiro',
            'valor_debito' => 'Débito',
            'valor_credito' => 'Crédito',
            'tipo' => 'Tipo',
            'dt_movimento'=>'Data',
            'observacao' => 'Observação'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkVenda() {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }

    public function getTipo() {
        return [$this->getStringAbertura(), 'Entrada - Recebimento de Pagamento', 'Saída - Pagamento de Despesa', 'Sangria'];
    }

    public function isAberturaCaixa() {
        return $this->tipo == 'Abertura de Caixa';
    }

    public function isEntrada() {
        return $this->tipo == 'Entrada - Recebimento de Pagamento';
    }

    public function isSaidaOuSangria() {
        return $this->tipo == 'Saída - Pagamento de Despesa' || $this->tipo == 'Sangria';
    }
     public function isSangria() {
        return $this->tipo == 'Sangria';
    }

    public function isPgtoDespesa() {
        return $this->tipo == 'Saída - Pagamento de Despesa';
    }

    public function getStringAbertura() {
        return 'Abertura de Caixa';
    }

}
