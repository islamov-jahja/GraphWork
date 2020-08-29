<?php


namespace app\infrastructure\services\graph\dto;


use yii\base\Model;

class VertexDTO extends Model
{
    private $name;
    private $graphId;

    public function __construct(?string $name, ?int $graphId, $config = [])
    {
        parent::__construct($config);

        $this->name = $name;
        $this->graphId = $graphId;
    }

    public function rules()
    {
        return [
            [['name', 'graphId'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['graphId'], 'integer', 'min' => 1]
        ];
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGraphId(): ?int
    {
        return $this->graphId;
    }
}