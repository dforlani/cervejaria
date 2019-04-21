<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuario".
 *
 * @property string $login
 * @property string $nome
 * @property string $sobrenome
 * @property string $senha
 * @property string $email
 */
class Usuario extends ActiveRecord implements IdentityInterface {

    public $id;
    public $papel;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['login', 'nome', 'sobrenome', 'senha', 'papel', 'email'], 'required'],
            [['login', 'nome'], 'string', 'max' => 20],
            [['sobrenome', 'email'], 'string', 'max' => 60],
            [['email'], 'email'],
            [['senha'], 'string'],
            ['papel', 'exist', 'targetClass' => Papel::class, 'targetAttribute' => ['papel' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'login' => 'Login',
            'nome' => 'Nome',
            'sobrenome' => 'Sobrenome',
            'senha' => 'Senha',
            'email' => 'E-Mail',
            'papel' =>'Função'
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPapeis()
    {
        return $this->hasMany(UsuarioPapel::className(), ['user_id' => 'login']);
    }
    
    
    /**
     * O sistema de papeis do Yii permite que cada usuário tenha vários papéis, nós vamos simular que ele permite apenas 1
     */
    public function getPapel(){
        $papeis = $this->getPapeis()->all();;
    
        if(!empty($papeis))
            return $papeis[0]->item_name;
        return null;
    }
    /**
     * Antes de salvar, converte a senha em um HASH
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->senha = Yii::$app->getSecurity()->generatePasswordHash($this->senha);
        return true;
    }  
   

    /*     * **********************FUNÇÕES DE AUTENTICAÇÃO************************* */

    public static function findIdentity($id) {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException(); //I don't implement this method because I don't have any access token column in my database
    }

    public function getId() {
        return $this->login;
    }

    public function getUsername() {
        return $this->login;
    }

    public function getAuthKey() {
        return $this->senha; //Here I return a value of my authKey column
    }

    public function validateAuthKey($authKey) {
        return $this->senha === $authKey;
    }

    public static function findByUsername($username) {
        return self::findOne(['login' => $username]);
    }

    public function validatePassword($password) {
        return Yii::$app->getSecurity()->validatePassword($password, $this->senha);
    }

    /*     * *****************FIM DAS FUNÇÕES DE AUTENTICAÇÃO********************** */
}
