<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_venda".
 *
 * @property int $fk_venda
 * @property int $fk_preco
 * @property double $quantidade
 * @property string $preco_unitario
 * @property string $preco_final
 *
 * @property Venda $fkVenda
 * @property Preco $fkPreco
 *  @property string $dt_inclusao
 */
class ItemVenda extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'item_venda';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_venda', 'fk_preco', 'quantidade'], 'required'],
            [['fk_venda', 'fk_preco'], 'integer'],
            [['dt_inclusao'], 'safe'],
            [['quantidade', 'preco_unitario', 'preco_final'], 'number'],
            [['fk_venda'], 'exist', 'skipOnError' => true, 'targetClass' => Venda::className(), 'targetAttribute' => ['fk_venda' => 'pk_venda']],
            [['fk_preco'], 'exist', 'skipOnError' => true, 'targetClass' => Preco::className(), 'targetAttribute' => ['fk_preco' => 'pk_preco']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'fk_venda' => 'Fk Venda',
            'fk_preco' => 'Fk Preco',
            'quantidade' => 'Qtd',
            'preco_unitario' => 'Pr. Unit.',
            'preco_final' => 'Pr. Final',
            'dt_inclusao' => 'Inclusão'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVenda() {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPreco() {
        return $this->hasOne(Preco::className(), ['pk_preco' => 'fk_preco']);
    }


    public function afterSave($insert, $changedAttributes) {
        //atualiza a quantidade de produtos disponiveis atuais
        $produto = $this->preco->produto;
        $produto->estoque_vendido = $produto->estoque_vendido + $this->quantidade * $this->preco->quantidade;
        $produto->save();
        if($produto->estoque_minimo >= ( $produto->estoque_inicial - $produto->estoque_vendido))
              Yii::$app->session->setFlash('error', "Estoque mínimo para o produto {$produto->nome} atingido.");
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function afterDelete() {
        $produto = $this->preco->produto;
        $produto->estoque_vendido = $produto->estoque_vendido - $this->quantidade * $this->preco->quantidade;
        $produto->save();
        parent::afterDelete();
    }

}
