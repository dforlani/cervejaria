<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "item_venda".
 *
 * @property int $fk_venda
 * @property int $fk_preco
 * @property double $quantidade
 * @property string $preco_unitario
 * @property string $preco_final
 * @property boolean $is_desconto_promocional
 * @property boolean $is_venda_app
 *
 * @property Venda $fkVenda
 * @property Preco $fkPreco
 *  @property string $dt_inclusao
 */
class ItemVenda extends ActiveRecord {

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
            [['fk_venda', 'fk_preco', 'quantidade', 'preco_unitario', 'preco_final'], 'required'],
            [['fk_venda', 'fk_preco'], 'integer'],
            [['dt_inclusao'], 'safe'],
            [['preco_unitario', 'preco_final', 'preco_custo_item'], 'number'],
            [['quantidade'], 'number', 'min' => 0],
            [['is_venda_app'], 'safe'],
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
            'dt_inclusao' => 'Inclusão',
            'preco_custo_item' => 'Preço de Custo do Item',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getVenda() {
        return $this->hasOne(Venda::className(), ['pk_venda' => 'fk_venda']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPreco() {
        return $this->hasOne(Preco::className(), ['pk_preco' => 'fk_preco']);
    }

    public function afterSave($insert, $changedAttributes) {
        $produto = $this->preco->produto;


        //atualiza a quantidade de produtos disponiveis atuais
        //TODO CÓDIGO ABAIXO MANTIDO PRA MANTER COMPATIBILIDADE DE QUANDO AINDA NÃO HAVIA O SISTEMA DE ENTRADAS, REMOVÊ-LO SE TUDO DER CERTO
        $produto->estoque_vendido = $produto->estoque_vendido + $this->quantidade * $this->preco->quantidade;
        $produto->save();


//        if ($produto->estoque_minimo >= ( $produto->getEstoqueTotal() - $produto->estoque_vendido)) {
//            Yii::$app->session->setFlash('error', "Estoque mínimo para o produto {$produto->nome} atingido.");
//        }
        //FIM DO TODO DE REMOÇÃO POR COMPATIBILIDADE
        //Substituição pelo sistema antigo de controle, obtem a entrada que está ativa, pra adicionar o vendido
        if (!empty($produto)) {
            $entrada_ativa = $produto->getEntradaAtiva();
            if (!empty($entrada_ativa)) {
                $entrada_ativa->quantidade_vendida = $entrada_ativa->quantidade_vendida + $this->quantidade * $this->preco->quantidade;
                $entrada_ativa->save();
            }

            if ($produto->estoque_minimo >= $entrada_ativa->getEstoqueDisponivel()) {
                Yii::$app->session->setFlash('error', "Estoque mínimo para o produto {$produto->nome} atingido.");
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public function beforeValidate() {
        $this->preco_final = $this->preco_unitario * $this->quantidade;
        if (!empty($this->preco) && !empty($this->preco->produto)) {
            if (!$this->is_desconto_promocional) {
                $this->preco_custo_item = $this->preco->produto->custo_compra_producao * $this->preco->quantidade * $this->quantidade;
            }
        } else {
            $this->preco_custo_item = 0;
        }
        return parent::beforeValidate();
    }

    public function afterDelete() {
        $produto = $this->preco->produto;


        //TODO CÓDIGO ABAIXO MANTIDO PRA MANTER COMPATIBILIDADE DE QUANDO AINDA NÃO HAVIA O SISTEMA DE ENTRADAS, REMOVÊ-LO SE TUDO DER CERTO
        //retorno o valor do estoque se cancelar a venda do item
        $produto->estoque_vendido = $produto->estoque_vendido - $this->quantidade * $this->preco->quantidade;
        $produto->save();
        //FIM DO TODO DE REMOÇÃO POR COMPATIBILIDADE
        //obtem a entrada que está ativa, pra devolver a quantidade vendida pro estoque
        if (!empty($produto)) {
            $entrada_ativa = $produto->getEntradaAtiva();
            if (!empty($entrada_ativa)) {
                $entrada_ativa->quantidade_vendida = $entrada_ativa->quantidade_vendida - $this->quantidade * $this->preco->quantidade;
                $entrada_ativa->save();
            }
        }


        parent::afterDelete();
    }

}
