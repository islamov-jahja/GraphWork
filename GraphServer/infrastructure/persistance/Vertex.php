<?php

namespace app\infrastructure\persistance;

use Yii;

/**
 * This is the model class for table "vertex".
 *
 * @property int $id
 * @property int|null $graph_id
 * @property string|null $name
 *
 * @property Edge[] $edges
 * @property Edge[] $edges0
 * @property Graph $graph
 */
class Vertex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vertex';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['graph_id'], 'default', 'value' => null],
            [['graph_id'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['graph_id'], 'exist', 'skipOnError' => true, 'targetClass' => Graph::className(), 'targetAttribute' => ['graph_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'graph_id' => 'Graph ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Edges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdges()
    {
        return $this->hasMany(Edge::className(), ['first_vertex_id' => 'id']);
    }

    /**
     * Gets query for [[Edges0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdges0()
    {
        return $this->hasMany(Edge::className(), ['second_vertex_id' => 'id']);
    }

    /**
     * Gets query for [[Graph]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGraph()
    {
        return $this->hasOne(Graph::className(), ['id' => 'graph_id']);
    }
}
