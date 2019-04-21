<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "papel".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Regra $ruleName
 * @property PapelHierarquia[] $papelHierarquias
 * @property PapelHierarquia[] $papelHierarquias0
 * @property Papel[] $children
 * @property Papel[] $parents
 * @property UsuarioPapel[] $usuarioPapels
 * @property Usuario[] $users
 */
class Papel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'papel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            ['rule_name', 'default', 'value' => null],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => Regra::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Description',
            'rule_name' => 'Rule Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(Regra::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPapelHierarquias()
    {
        return $this->hasMany(PapelHierarquia::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPapelHierarquias0()
    {
        return $this->hasMany(PapelHierarquia::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Papel::className(), ['name' => 'child'])->viaTable('papel_hierarquia', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(Papel::className(), ['name' => 'parent'])->viaTable('papel_hierarquia', ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioPapels()
    {
        return $this->hasMany(UsuarioPapel::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Usuario::className(), ['login' => 'user_id'])->viaTable('usuario_papel', ['item_name' => 'name']);
    }
}
