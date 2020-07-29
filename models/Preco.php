<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preco".
 *
 * @property int $pk_preco
 * @property int $fk_produto
 * @property int $codigo_barras
 * @property int $tipo_cardapio
 * @property string $denominacao
 * @property string $preco
 * @property double $quantidade
 * @property double $promocao_quantidade_atingir
 * @property double $promocao_desconto_aplicar
 * @property boolean $is_promocao_ativa
 * @property ItemVenda[] $itemVendas
 * @property Produto $fkProduto
 */
class Preco extends \yii\db\ActiveRecord {

    
    public static $TIPO_CARDAPIO_NENHUM = 'nenhum';
    public static $TIPO_CARDAPIO_TAP_LIST = 'tap_list';
    public static $TIPO_CARDAPIO_CARDAPIO = 'cardapio';
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
            [['preco', 'quantidade', 'promocao_quantidade_atingir', 'promocao_desconto_aplicar'], 'number'],
            [['denominacao'], 'string', 'max' => 100],
            [['promocao_desconto_aplicar'], 'number', 'max' => 0.0000001, 'tooBig' => 'O Valor do Desconto deve ser negativo'],
            [['codigo_barras'], 'number', 'min' => 100000000001, 'max' => 999999999999,],
            [['is_promocao_ativa'], 'boolean'],
            [['pk_preco'], 'unique'],
    
            [['tipo_cardapio'], 'safe'],
            [['codigo_barras'], 'unique'],
            [['pos_cardapio'], 'validateValorUnicoCardapio'],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
            [['is_promocao_ativa'], 'validatePromocao']
        ];
    }

    /**
     * Valida se tem valores caso tenha ativa a promoção
     * @param type $attribute
     * @param type $params
     */
    public function validatePromocao($attribute, $params) {
        if ($this->is_promocao_ativa) {            
            if (($this->promocao_desconto_aplicar == 0) || ($this->promocao_quantidade_atingir == 0)) {
                $this->addError($attribute, 'Para a promoção ficar ativa, "Quantidade a Atingir" e "Desconto a Aplicar" não podem estar em branco.');
            }
        }
    }

    public function validateValorUnicoCardapio($attribute, $params) {

        if ($this->isTapList() || $this->isCardapioApp()) {
            if ($this->isNewRecord) {
                $precos = Preco::find()->where('tipo_cardapio like :tipo_cardapio AND pos_cardapio = :pos_cardapio', [':tipo_cardapio'=>$this->tipo_cardapio, ':pos_cardapio'=>$this->pos_cardapio])->all();
            } else {
                $precos = Preco::find()->where('tipo_cardapio like :tipo_cardapio AND pos_cardapio = :pos_cardapio  AND pk_preco != :pk_preco', [':tipo_cardapio'=>$this->tipo_cardapio, ':pos_cardapio'=>$this->pos_cardapio, ':pk_preco'=> $this->pk_preco])->all();
            }
            if (!empty($precos)) {
                $this->addError($attribute, 'Posição já está sendo utilizada no produto: ' . $precos[0]->produto->nome . ' -> ' . $precos[0]->denominacao);
            }
        } else {
            $this->pos_cardapio = null;
        }
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
        if (!empty($codigo)) {
            $codigo = ' - ' . $codigo;
        }
        return $this->produto->nome . ' - ' . $this->denominacao . ' ' . $codigo;
    }

    public function getNomeProdutoPlusDenominacaoSemBarras() {
        return $this->produto->nome . ' - ' . $this->denominacao;
    }
    
    public function getNomeProdutoPlusDenominacaoSemBarrasLimitado($limit) {
        return substr($this->getNomeProdutoPlusDenominacaoSemBarras(), 0, $limit);
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
            'pos_cardapio' => 'Posição',
            'promocao_quantidade_atingir' => 'Quantidade Comprada pra Atingir',
            'promocao_desconto_aplicar' => 'Desconto a Aplicar',
            'is_promocao_ativa' => 'Promoção está Ativa?',
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

    public static function getMaiorPosicaoTipoCardapio($tipo_cardapio) {
        $pos = Preco::find()->where('tipo_cardapio = :tipo_cardapio', [':tipo_cardapio'=>$tipo_cardapio])->max('pos_cardapio');
     
        if(empty($pos))
            return 0;
        return $pos;
    }
    

    
    public function isTapList(){
        return $this->tipo_cardapio == Preco::$TIPO_CARDAPIO_TAP_LIST;
    }

       public function isCardapioApp(){
        return $this->tipo_cardapio == Preco::$TIPO_CARDAPIO_CARDAPIO;
    }
    
}
