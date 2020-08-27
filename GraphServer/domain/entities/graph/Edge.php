<?php


namespace app\domain\entities\graph;


class Edge
{
    private $id;
    private $name;
    private $vertexes;
    private $wasDelete;


    /**
     * @param int $id
     * @param string $name
     * @param Vertex[] $vertexes
     */
    public function __construct(int $id, string $name, array $vertexes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->wasDelete = false;
        $this->vertexes = $vertexes;
    }

    public function delete(): void
    {
        $this->wasDelete = true;
    }

    public function addVertex(Vertex $vertex)
    {
        $this->vertexes[] = $vertex;
    }

    /**
     * @return Vertex[]
     */
    public function getDeletedVertexes(): array
    {
        $deletedVertexes = [];

        foreach ($this->vertexes as $vertex){
            if ($vertex->wasDelete()){
                $deletedVertexes[] = $vertex;
            }
        }

        return $deletedVertexes;
    }

    /**
     * @return Vertex[]
     */
    public function getChangedVertexes(): array
    {
        $changedVertexes = [];

        foreach ($this->vertexes as $vertex){
            if ($vertex->wasChanged()){
                $changedVertexes[] = $vertex;
            }
        }

        return $changedVertexes;
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @return array
     */
    public function getVertexes(): array
    {
        return $this->vertexes;
    }

    public function wasDelete(): bool
    {
        return $this->wasDelete;
    }
}