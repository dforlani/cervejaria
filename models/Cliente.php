<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $pk_cliente
 * @property string $nome
 * @property string $telefone
 * @property string $cpf
 * @property string $dt_nascimento
 *
 * @property Venda[] $vendas
 */
class Cliente extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['pk_cliente'], 'integer'],
            [['dt_nascimento'], 'safe'],
            [['nome'], 'string', 'max' => 200],
            [['nome'], 'unique'],            
            [['telefone'], 'string', 'max' => 10],
            [['cpf'], 'string', 'max' => 14],
            [['pk_cliente'], 'unique'],
            [['dt_nascimento'], 'default']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'pk_cliente' => 'Pk Cliente',
            'nome' => 'Nome',
            'telefone' => 'Telefone',
            'cpf' => 'CPF',
            'dt_nascimento' => 'Data de Nascimento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendas() {
        return $this->hasMany(Venda::className(), ['fk_cliente' => 'pk_cliente']);
    }

    public function beforeSave($insert) {
    //        if (!empty($this->dt_nascimento))
    //            $this->dt_nascimento = Yii::$app->formatter->asDate($this->dt_nascimento,  'php:Y-m-d');
        //Yii::$app->formatter->asDate($dateStr, $fmt);
        return parent::beforeSave($insert);
    }
    
    public static function getClientesSemVendasAbertas(){
        return Cliente::findBySql('select * from cliente where pk_cliente not in (select fk_cliente from venda where estado = "aberta" and fk_cliente is not null)')->all();
    }

}
