<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produto".
 *
 * @property int $pk_produto
 * @property string $nome
 * @property double $estoque_vendido
 * @property int $fk_unidade_medida
 * @property int $is_vendavel
 * @property string $custo_compra_producao
 * @property Entrada[] $entradas
 * @property Preco[] $precos
 */
class Produto extends \yii\db\ActiveRecord {

    public static $TIPO_OUTRO = 'Outro';
    public static $TIPO_CERVEJA = 'Cerveja';
    public $auxHasPromocao;
    public static $URL = "produto/update";
    
    public $custo_fabricacao;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['fk_unidade_medida'], 'required'],
            [['fk_unidade_medida'], 'integer'],
            [['estoque_vendido', 'estoque_inicial', 'estoque_minimo', 'custo_compra_producao', 'ibu', 'teor_alcoolico',], 'number'],
            [['nome',], 'string', 'max' => 100],
            [['nr_lote',], 'string', 'max' => 20],
            [['pk_produto'], 'unique'],
            [['custo_fabricacao'], 'safe'],
            [['dt_fabricacao'], 'default'],
            [['dt_vencimento'], 'default'],
            [['is_vendavel'], 'required'],
            [['tipo_produto'], 'safe'],
            [['dt_fabricacao', 'dt_vencimento'], 'validateData'],
        ];
    }

    public function validateData($attribute, $params) {
        //cria uma data 10 anos no passado para comparação
        $date = new \DateTime();
        date_sub($date, date_interval_create_from_date_string('10 years'));
        $minAgeDate = date_format($date, 'Y-m-d');

        //cria uma data 10 anos no futuro para comparação
        $date = new \DateTime();
        date_add($date, date_interval_create_from_date_string('10 years'));
        $maxAgeDate = date_format($date, 'Y-m-d');

        if ($this->$attribute < $minAgeDate) {
            $this->addError($attribute, 'A data é muito antiga.');
        } elseif ($this->$attribute > $maxAgeDate) {

            $this->addError($attribute, 'A data está muito no futuro.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_produto' => 'Pk Produto',
            'nome' => 'Nome',
            'estoque_inicial' => 'Estoq. Inicial',
            'estoque_vendido' => 'Estoq. Vendido',
            'fk_unidade_medida' => 'Unidade de Medida',
            'is_vendavel' => 'É para vender?',
            'estoque_minimo' => 'Estoq. Mínimo',
            'dt_fabricacao' => 'Envase',
            'dt_vencimento' => 'Vencimento',
            'nr_lote' => 'Número do Lote',
            'teor_alcoolico' => 'Teor Alcoólico',
            'ibu' => 'IBU',
            'custo_fabricacao' =>"Custo Fabricação",
            'tipo_produto' => 'Tipo de Produto',
            'custo_compra_producao' => 'Preço de Custo da Unidade de Medida'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntradas() {
        return $this->hasMany(Entrada::className(), ['fk_produto' => 'pk_produto']);
    }

    public function getUnidadeMedida() {
        return $this->hasOne(UnidadeMedida::className(), ['pk_unidade_medida' => 'fk_unidade_medida']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrecos() {
        return $this->hasMany(Preco::className(), ['fk_produto' => 'pk_produto']);
    }

    public function beforeSave($insert) {
        return parent::beforeSave($insert);
    }

    public function hasPromocaoAtiva() {
        $precos = $this->precos;
        if (!empty($precos)) {
            foreach ($precos as $preco) {
                if ($preco->is_promocao_ativa)
                    return 'Sim';
            }
        }
        return 'Não';
    }

    public static function getTiposProdutos() {
        return ['Cerveja' => 'Cerveja', 'Outro' => 'Outro'];
    }

    public function isCerveja() {
        return $this->tipo_produto == 'Cerveja';
    }

    /**
     * Busca produtos que vão vencer em alguns dias
     * @return type
     */
    public static function getProdutosPraVencer($tipo_produto = 'Outro') {

        return Produto::find()->where('dt_vencimento  <= NOW() + interval 15 day  AND dt_vencimento > NOW() AND tipo_produto like "' . $tipo_produto . '"')->orderBy('dt_vencimento ASC')
                        ->all();
    }

    /**
     * return produtos que estão vencidos
     * @return type
     */
    public static function getProdutosVencidos($tipo_produto = 'Outro') {
      
        return Produto::find()->where('dt_vencimento  <= NOW() AND tipo_produto like "' . $tipo_produto. '"')->orderBy('dt_vencimento ASC')->all();
    }

    /**
     * Retorna todos os produtos com promoção ativa
     * @return type
     */
    public static function getProdutosComPromocaoAtiva($tipo_produto = 'Outro') {
        return Produto::find()->where('is_promocao_ativa IS TRUE AND tipo_produto like "' . $tipo_produto. '"')->joinWith('precos')->orderBy('nome')->all();
    }

    
    /**
     * Retorna a soma de todos os estoques de entradas já adicionados
     */
    public function getEstoqueTotal(){
        $total = 0;
        $entradas = $this->entradas;
        if(!empty($entradas)){
            foreach($entradas as $entrada){        
                $total += empty($entrada->quantidade)? 0:$entrada->quantidade;
            }
        }   
        return $total;
    }
    
    public function getEntradaAtiva(){
        return Entrada::find()->where(['fk_produto'=>$this->pk_produto])->one();
    }
}
