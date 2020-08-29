<?php


namespace app\domain\entities\graph;


use app\domain\exceptions\NotFoundException;

class Graph
{
    private $id;
    private $name;
    private $vertexes;
    private $userId;
    private $needToDelete;
    private $needToSave;

    /**
     * @param string $name
     * @param int|null $userId
     * @param int|null $id
     * @param Vertex[] $vertexes
     */
    public function __construct(string $name, ?int $userId, ?int $id = null, array $vertexes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->vertexes = $vertexes;
        $this->userId = $userId;
        $this->needToDelete = false;
        $this->needToSave = false;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function save(): void
    {
        $this->needToSave = true;
    }

    public function needToSave()
    {
        return $this->needToSave;
    }

    public function delete(): void
    {
        $this->needToDelete = true;
    }

    public function needToDelete(): bool
    {
        return $this->needToDelete;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function addVertex(Vertex $vertex)
    {
        $this->vertexes[] = $vertex;
    }

    /**
     * @return Vertex[]
     */
    public function getVertexes(): array
    {
        return $this->vertexes;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $edgeId
     * @param $weight
     * @return void
     * @throws NotFoundException
     */
    public function changeWeightOfEdge(int $edgeId, $weight)
    {
        foreach ($this->vertexes as $vertex){
            $edges = array_filter($vertex->getEdges(), function ($edge) use($edgeId){
                return $edge->getId() === $edgeId;
            });

            if (!empty($edges)){
                $edges[0]->setWeight($weight);
                return;
            }
        }

        throw new NotFoundException('Такого ребра не существует');
    }

    /**
     * @return Edge[]
     */
    public function getEdgesToChange(): array
    {
        $edgesToDelete = [];

        foreach ($this->vertexes as $edge){
            $edgesToDelete = array_merge($edgesToDelete, $edge->getChangedEdges());
        }

        return $edgesToDelete;
    }

    /**
     * @return Vertex[]
     */
    public function getVertexesToDelete()
    {
        $deletedVertexes = [];

        foreach ($this->vertexes as $edge){
            if ($edge->needToDelete()){
                $deletedVertexes[] = $edge;
            }
        }

        return $deletedVertexes;
    }

    /**
     * @return Edge[]
     */
    public function getEdgesToDelete()
    {
        $deletedEdges = [];

        foreach ($this->vertexes as $vertex){
            $deletedEdges = array_merge($deletedEdges, $vertex->getDeletedEdges());
        }

        return $deletedEdges;
    }

    /**
     * @param int $vertexId
     * @throws NotFoundException
     */
    public function deleteVertex(int $vertexId)
    {
        foreach ($this->vertexes as $vertex){
            if ($vertex->getId() === $vertexId){
                $vertex->delete();
                return;
            }
        }

        throw new NotFoundException('Данная вершина не найдена');
    }

    /**
     * @param int $edgeId
     * @throws NotFoundException
     */
    public function deleteEdge(int $edgeId)
    {
        foreach ($this->vertexes as $vertex){
            $edges = array_filter($vertex->getEdges(), function ($edge) use($edgeId){
                return $edge->getId() === $edgeId;
            });

            if (!empty($edges)){
                $this->deletePairOfEdges($vertex->getId(), $edges[0]);
                return;
            }
        }

        throw new NotFoundException('Такого ребра не существует');
    }

    public function getVertexById(int $vertexId): Vertex
    {
        foreach ($this->vertexes as $vertex){
            if ($vertex->getId() === $vertexId){
                return $vertex;
            }
        }

        throw new NotFoundException();
    }

    private function deletePairOfEdges(int $vertexId , Edge $edge)
    {
        $edge->delete();
        $vertex = $edge->getVertex();

        $edges = array_filter($vertex->getEdges(), function ($edge) use($vertexId){
            return $edge->getVertex()->getId() === $vertexId;
        });

        $edges[0]->delete();
    }

    public function toArray()
    {
        $graphInArray['name'] = $this->name;
        $graphInArray['id'] = $this->id;
        $graphInArray['userId'] = $this->userId;

        foreach ($this->vertexes as $vertex) {
            $graphInArray['vertexes'][] = $vertex->toArray();
        }

        return $graphInArray;
    }
}