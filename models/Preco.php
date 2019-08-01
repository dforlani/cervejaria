<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preco".
 *
 * @property int $pk_preco
 * @property int $fk_produto
 * @property int $codigo_barras
 * @property string $denominacao
 * @property string $preco
 * @property double $quantidade
 *
 * @property ItemVenda[] $itemVendas
 * @property Produto $fkProduto
 */
class Preco extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'preco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_produto'], 'required'],
            [['pk_preco', 'fk_produto', 'codigo_barras'], 'integer'],
            [['preco', 'quantidade'], 'number'],
            [['denominacao'], 'string', 'max' => 100],
            [['pk_preco'], 'unique'],
            [['codigo_barras'], 'unique'],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
        ];
    }

    public static function getArrayProdutosPrecos() {
        $lista = [];
        $precos = Preco::find()->joinWith('produto')->orderBy('nome, denominacao')->all();
        foreach ($precos as $preco) {
            $lista[$preco->pk_preco] = $preco->getNomeProdutoPlusDenominacao();
        }
        return $lista;
    }

    public function getNomeProdutoPlusDenominacao() {
        return $this->produto->nome . ' - ' . $this->denominacao . ' - ' . $this->quantidade . ' ' . $this->produto->unidadeMedida->unidade_medida;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_preco' => 'Pk Preco',
            'fk_produto' => 'Fk Produto',
            'denominacao' => 'Denominacao',
            'preco' => 'Preco',
            'quantidade' => 'Quantidade',
            'codigo_barras' => 'Código de Barras'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemVendas() {
        return $this->hasMany(ItemVenda::className(), ['fk_preco' => 'pk_preco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduto() {
        return $this->hasOne(Produto::className(), ['pk_produto' => 'fk_produto']);
    }

}
