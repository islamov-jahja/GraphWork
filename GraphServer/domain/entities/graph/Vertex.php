<?php


namespace app\domain\entities\graph;


class Vertex
{
    private $id;
    private $weight;
    private $edge;
    private $wasDelete;
    private $wasChanged;

    public function __construct(int $id, int $weight, Edge $edge)
    {
        $this->edge = $edge;
        $this->id = $id;
        $this->weight = $weight;
        $this->wasDelete = false;
        $this->wasChanged = false;
    }

    /**
     * @return Edge
     */
    public function getEdge(): Edge
    {
        return $this->edge;
    }

    public function setWeight(int $weight)
    {
        $this->weight = $weight;
        $this->wasChanged = true;
    }

    public function delete(): void
    {
        $this->wasDelete = true;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function wasChanged(): bool
    {
        return $this->wasChanged;
    }

    public function wasDelete(): bool
    {
        return $this->wasDelete;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }
}