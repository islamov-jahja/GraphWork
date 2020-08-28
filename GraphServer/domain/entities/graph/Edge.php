<?php


namespace app\domain\entities\graph;


class Edge
{
    private $id;
    private $weight;
    private $vertex;
    private $needToDelete;
    private $needToChange;
    private $needToSave;

    public function __construct(int $id, int $weight, Vertex $vertex)
    {
        $this->vertex = $vertex;
        $this->id = $id;
        $this->weight = $weight;
        $this->needToDelete = false;
        $this->needToChange = false;
        $this->needToSave = false;
    }

    /**
     * @return Vertex
     */
    public function getVertex(): Vertex
    {
        return $this->vertex;
    }

    public function save(): void
    {
        $this->needToSave = true;
    }

    /**
     * @return false
     */
    public function needToSave()
    {
        return $this->needToSave;
    }

    public function setWeight(int $weight)
    {
        $this->weight = $weight;
        $this->needToChange = true;
    }

    public function delete(): void
    {
        $this->needToDelete = true;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function needToChange(): bool
    {
        return $this->needToChange;
    }

    public function needToDelete(): bool
    {
        return $this->needToDelete;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }
}