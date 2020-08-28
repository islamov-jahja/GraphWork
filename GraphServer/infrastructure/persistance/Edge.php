<?php

namespace app\infrastructure\persistance;

use Yii;

/**
 * This is the model class for table "edge".
 *
 * @property int $id
 * @property int|null $first_vertex_id
 * @property int|null $second_vertex_id
 * @property int|null $weight
 *
 * @property Vertex $firstVertex
 * @property Vertex $secondVertex
 */
class Edge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_vertex_id', 'second_vertex_id', 'weight'], 'default', 'value' => null],
            [['first_vertex_id', 'second_vertex_id', 'weight'], 'integer'],
            [['first_vertex_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vertex::className(), 'targetAttribute' => ['first_vertex_id' => 'id']],
            [['second_vertex_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vertex::className(), 'targetAttribute' => ['second_vertex_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_vertex_id' => 'First Edge ID',
            'second_vertex_id' => 'Second Edge ID',
            'weight' => 'Weight',
        ];
    }

    /**
     * Gets query for [[FirstVertex]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFirstVertex()
    {
        return $this->hasOne(Vertex::className(), ['id' => 'first_vertex_id']);
    }

    /**
     * Gets query for [[SecondVertex]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSecondVertex()
    {
        return $this->hasOne(Vertex::className(), ['id' => 'second_vertex_id']);
    }
}
