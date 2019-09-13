<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preco".
 *
 * @property int $pk_preco
 * @property int $fk_produto
 * @property int $codigo_barras
 * @property int $is_tap_list
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
            [['fk_produto',], 'required'],
            [['pk_preco', 'fk_produto', 'codigo_barras'], 'integer'],
            [['preco', 'quantidade'], 'number'],
            [['denominacao'], 'string', 'max' => 100],
            [['codigo_barras'], 'number', 'min' => 100000000001, 'max' => 999999999999],
            [['pk_preco'], 'unique'],
            [['is_tap_list'], 'safe'],
            [['codigo_barras'], 'unique'],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
        ];
    }

    public static function getArrayProdutosPrecos() {
        $lista = [];
        $precos = Preco::find()->where('is_vendavel IS TRUE')->joinWith('produto')->orderBy('nome, denominacao')->all();
        foreach ($precos as $preco) {
            $lista[$preco->pk_preco] = $preco->getNomeProdutoPlusDenominacao();
        }
        return $lista;
    }

    public function getNomeProdutoPlusDenominacao() {
        $codigo = $this->getCodigoBarrasComDigitoVerificador();
        if (!empty($codigo))
            $codigo = ' - ' . $codigo;
        return $this->produto->nome . ' - ' . $this->denominacao . ' ' . $codigo;
    }

    public function getNomeProdutoPlusDenominacaoSemBarras() {
        return $this->produto->nome . ' - ' . $this->denominacao;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_preco' => 'Pk Preco',
            'fk_produto' => 'Fk Produto',
            'denominacao' => 'Denominação',
            'preco' => 'Preço',
            'quantidade' => 'Quantidade',
            'codigo_barras' => 'Código de Barras',
            'is_tap_list'=>'Tap List'
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

    public static function getSugestaoCodigoBarras() {
        $sg = Preco::findBySql('SELECT MAX(codigo_barras) as codigo_barras FROM `preco`')->one();
        if (empty($sg->codigo_barras) || ($sg->codigo_barras < 100000000001)) {
            return 100000000001;
        } else {
            return $sg->codigo_barras + 1;
        }
    }

    public function getCodigoBarrasComDigitoVerificador() {
        if (!empty($this->codigo_barras)) {
            $multiplicador = 1;
            $soma = 0;

            for ($index = 0; $index < strlen($this->codigo_barras); $index++) {
                $soma = $soma + substr($this->codigo_barras, $index, 1) * $multiplicador;
                $multiplicador = ($multiplicador == 1 ? 3 : 1);
                //echo $soma;
            }

            $resultado = $soma / 10;


            $resultado = floor($resultado);
            $resultado = $resultado + 1;
            $resultado = $resultado * 10;
            $resultado = $resultado - $soma;

            if ($resultado >= 10)
                $resultado = 0;

            return $this->codigo_barras . '' . $resultado;
        }else {
            return '';
        }
    }

}
