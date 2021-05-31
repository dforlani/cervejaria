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
 * @property float|null $quantidade_vendida
 * @property string|null $dt_entrada
 * @property float|null $custo_fabricacao
 * @property string|null $dt_fabricacao
 * @property string|null $dt_vencimento
 * @property string|null $nr_lote
 *
 * @property Produto $fkProduto
 * @property Usuario $fkUsuario
 */
class Entrada extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'entrada';
    }

    public function __construct($fk_produto = null, $quantidade = null, $custo_fabricacao = null, $dt_fabricacao = null, $dt_vencimento = null, $nr_lote = null) {
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
    public function rules() {
        return [
            [['fk_usuario'], 'required'],
            [['fk_produto'], 'integer'],
            [['is_ativo'], 'boolean'],
            [['quantidade', 'quantidade_vendida', 'custo_fabricacao'], 'number'],
            [['dt_entrada', 'dt_fabricacao', 'dt_vencimento'], 'safe'],
            [['fk_usuario'], 'string', 'max' => 20],
            [['nr_lote'], 'string', 'max' => 50],
            [['fk_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['fk_produto' => 'pk_produto']],
            [['fk_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['fk_usuario' => 'login']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_entrada' => 'Pk Entrada',
            'fk_usuario' => 'Fk Usuario',
            'fk_produto' => 'Fk Produto',
            'quantidade' => 'Quantidade',
            'quantidade_vendida' => 'Quantidade Vendida',
            'dt_entrada' => 'Dt Entrada',
            'custo_fabricacao' => 'Custo Fabricação',
            'dt_fabricacao' => 'Data Fabricação',
            'dt_vencimento' => 'Data Vencimento',
            'nr_lote' => 'Nr Lote',
            'is_ativo' => 'Ativo'
        ];
    }

    /**
     * Gets query for [[FkProduto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto() {
        return $this->hasOne(Produto::className(), ['pk_produto' => 'fk_produto']);
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        ///como só pode ter um ativo por vez, busca qual é o ativo e altera os demais pra não ativos
        if ($this->is_ativo) {
            if (!empty($this->pk_entrada)) {
                Entrada::updateAll(['is_ativo' => false], 'fk_produto = ' . $this->fk_produto . ' AND pk_entrada != ' . $this->pk_entrada);
            } else {
                Entrada::updateAll(['is_ativo' => false], 'fk_produto = ' . $this->fk_produto);
            }
        }
        return true;
    }

    public function getEstoqueDisponivel() {
        return $this->quantidade - $this->quantidade_vendida;
    }

    /**
     * Gets query for [[FkUsuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkUsuario() {
        return $this->hasOne(Usuario::className(), ['login' => 'fk_usuario']);
    }

}
