<?php


namespace app\domain\entities\graph;


class Vertex
{
    private $id;
    private $name;
    private $edges;
    private $needToDelete;
    private $needToSave;

    /**
     * @param string $name
     * @param int|null $id
     * @param Edge[] $edges
     */
    public function __construct(string $name, ?int $id = null, array $edges = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->needToDelete = false;
        $this->needToSave = false;
        $this->edges = $edges;
    }

    public function save()
    {
        $this->needToSave = true;
    }

    public function needToSave(): bool
    {
        return $this->needToSave;
    }

    public function delete(): void
    {
        $this->needToDelete = true;
    }

    public function addEdge(Edge $edge)
    {
        $this->edges[] = $edge;
    }

    /**
     * @return Edge[]
     */
    public function getDeletedEdges(): array
    {
        $deletedEdges = [];

        foreach ($this->edges as $edge){
            if ($edge->needToDelete()){
                $deletedEdges[] = $edge;
            }
        }

        return $deletedEdges;
    }

    /**
     * @return Edge[]
     */
    public function getChangedEdges(): array
    {
        $changedEdges = [];

        foreach ($this->edges as $edge){
            if ($edge->needToChange()){
                $changedEdges[] = $edge;
            }
        }

        return $changedEdges;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Edge[]
     */
    public function getEdges(): array
    {
        return $this->edges;
    }

    public function needToDelete(): bool
    {
        return $this->needToDelete;
    }
}