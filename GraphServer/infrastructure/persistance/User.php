<?php

namespace app\infrastructure\persistance;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $token
 * @property string|null $password
 *
 * @property Graph[] $graphs
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
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
            [['email'], 'unique'],
            [['email'], 'email'],
            [['name', 'email'], 'string', 'max' => 80],
            [['token', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'token' => 'Token',
            'password' => 'Password',
        ];
    }

    /**
     * Gets query for [[Graphs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraphs()
    {
        return $this->hasMany(Graph::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['token' => $token]);
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }
}
