<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $fio
 * @property string $username
 * @property string $password
 * @property string $token
 *
 * @property Lastlogin[] $lastlogins
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['id']);
        unset($fields['token']);
        unset($fields['password']);

        return $fields;
    }
    public $authKey;

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['fio', 'username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 30],
            [['token'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'Fio',
            'username' => 'Username',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastlogins()
    {
        return $this->hasMany(Lastlogin::className(), ['user_id' => 'id']);
    }

    public function login()
    {
        $user = User::findOne(['username' => $this->username]);

        if($user && $user->password == $this->password){
            $user->token = Yii::$app->security->generateRandomString(32);
            if($user->save()){
                return $user;
            }
        }
        return false;
    }
}
