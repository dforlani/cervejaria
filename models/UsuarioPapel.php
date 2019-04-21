<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario_papel".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property Usuario $user
 * @property Papel $itemName
 */
class UsuarioPapel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_papel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name'], 'string', 'max' => 64],
            [['user_id'], 'string', 'max' => 20],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['user_id' => 'login']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => Papel::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => 'Item Name',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuario::className(), ['login' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(Papel::className(), ['name' => 'item_name']);
    }
}
