<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "entrada".
 *
 * @property int $pk_entrada
 * @property string $fk_usuario
 * @property int|null $fk_produto
 * @property float|null $quantidade

 * @property string|null $dt_fabricacao
 * @property string|null $dt_vencimento
 * @property float|null $custo_fabricacao
 * @property string|null $nr_lote
 *
 * @property Produto $fkProduto
 * @property Usuario $fkUsuario
 */
class Entrada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entrada';
    }
    
    public function __construct($fk_produto = null, $quantidade= null, $custo_fabricacao= null, $dt_fabricacao= null, $dt_vencimento= null, $nr_lote= null ){
        $this->fk_produto = $fk_produto;
        $this->fk_usuario = 'dforlani';
        $this->quantidade = $quantidade;
        $this->custo_fabricacao = $custo_fabricacao;
        $this->dt_fabricacao = $dt_fabricacao;
        $this->dt_vencimento = $dt_vencimento;
        $this->nr_lote = $nr_lote;
    }
    
   
            
    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_usuario'], 'required'],
            [['fk_produto'], 'integer'],
            [['quantidade'], 'number'],
            [['custo_fabricacao'], 'safe'],
            [[ 'dt_fabricacao', 'dt_vencimento'], 'safe'],
            [['fk_usuario', 'nr_lote'], 'string', 'max' => 20],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
            [['fk_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario' => 'login']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pk_entrada' => 'Pk Entrada',
            'fk_usuario' => 'Fk Usuario',
            'fk_produto' => 'Fk Produto',
            'quantidade' => 'Quantidade',
            'dt_fabricacao' =>'Fabricação',
            
            'dt_vencimento' => 'Vencimento',
            'custo_fabricacao' => 'Custo de Fabricação',
            'nr_lote' => 'Lote',
        ];
    }

    /**
     * Gets query for [[FkProduto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::className(), ['pk_produto' => 'fk_produto']);
    }

    /**
     * Gets query for [[FkUsuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuario()
    {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario']);
    }
}
