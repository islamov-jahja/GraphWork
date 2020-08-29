<?php


namespace app\infrastructure\services\graph\dto;


use yii\base\Model;

class EdgeDTO extends Model
{
    private $weight;
    private $graphId;
    private $firstVertexId;
    private $secondVertexId;

    public function __construct(?int $weight, ?int $firstVertexId, ?int $secondVertexId, ?int $graphId, $config = [])
    {
        parent::__construct($config);
        $this->weight = $weight;
        $this->graphId = $graphId;
        $this->firstVertexId = $firstVertexId;
        $this->secondVertexId = $secondVertexId;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function getGraphId(): ?int
    {
        return $this->graphId;
    }

    public function getFirstVertexId(): ?int
    {
        return $this->firstVertexId;
    }

    public function getSecondVertexId(): ?int
    {
        return $this->secondVertexId;
    }

    public function rules()
    {
        return [
            [['graphId', 'weight'], 'required'],
            [['weight'], 'integer', 'min' => 0]
        ];
    }
}