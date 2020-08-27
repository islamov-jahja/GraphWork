<?php

namespace app\infrastructure\persistance;

use Yii;

/**
 * This is the model class for table "graph".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 *
 * @property User $user
 * @property Vertex[] $vertices
 */
class Graph extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'graph';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Vertices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVertices()
    {
        return $this->hasMany(Vertex::className(), ['graph_id' => 'id']);
    }
}
