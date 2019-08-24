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
            [['estoque_vendido', 'estoque_inicial', 'estoque_minimo', 'custo_compra_producao'], 'number'],
            [['nome',], 'string', 'max' => 100],
            [['nr_lote',], 'string', 'max' => 20],
            [['pk_produto'], 'unique'],
            [['dt_fabricacao'], 'default'],
            [['dt_vencimento'], 'default'],
            [['is_vendavel'], 'required'],
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

}
