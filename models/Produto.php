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
 *
 * @property Entrada[] $entradas
 * @property Preco[] $precos
 */
class Produto extends \yii\db\ActiveRecord {

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
            [['estoque_vendido', 'estoque_inicial', 'estoque_minimo'], 'number'],
            [['nome',], 'string', 'max' => 100],
            [['nr_lote',], 'string', 'max' => 20],
            [['pk_produto'], 'unique'],
            [['dt_fabricacao'], 'default'],
            [['dt_vencimento'], 'default'],
            [['is_vendavel'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_produto' => 'Pk Produto',
            'nome' => 'Nome',
            'estoque_inicial'=>'Estoq. Inicial',
            'estoque_vendido' => 'Estoq. Vendido',
            'fk_unidade_medida' => 'Unidade de Medida',
            'is_vendavel' => 'É para vender?',
            'estoque_minimo' => 'Estoq. Mínimo',
            'dt_fabricacao' => 'Fabricação',
            'dt_vencimento' => 'Vencimento',
            'nr_lote' => 'Número do Lote'
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
//        if (!empty($this->dt_vencimento))
//            $this->dt_vencimento = Yii::$app->formatter->asDate($this->dt_vencimento, 'yyyy-dd-MM');
//        if (!empty($this->dt_fabricacao))
//            $this->dt_fabricacao = Yii::$app->formatter->asDate($this->dt_fabricacao, 'yyyy-dd-MM');
        return parent::beforeSave($insert);
    }

}
