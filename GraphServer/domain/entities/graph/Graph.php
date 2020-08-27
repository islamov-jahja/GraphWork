<?php


namespace app\domain\entities\graph;


use app\domain\entities\User;
use app\domain\exceptions\NotFoundException;

class Graph
{
    private $id;
    private $name;
    private $edges;
    private $user;
    private $wasDelete;

    /**
     * @param int $id
     * @param string $name
     * @param User|null $user
     * @param Edge[] $edges
     */
    public function __construct(int $id, string $name, ?User $user, array $edges = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->edges = $edges;
        $this->user = $user;
        $this->wasDelete = false;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function delete(): void
    {
        $this->wasDelete = true;
    }

    public function wasDelete(): bool
    {
        return $this->wasDelete;
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $vertexId
     * @param $weight
     * @return void
     * @throws NotFoundException
     */
    public function changeWeightOfVertex(int $vertexId, $weight)
    {
        foreach ($this->edges as $edge){
            foreach ($edge->getVertexes() as $vertex){
                if ($vertex->getId() === $vertexId){
                    $vertex->setWeight($weight);
                    return;
                }
            }
        }

        throw new NotFoundException('Такого ребра не существует');
    }

    /**
     * @return Vertex[]
     */
    public function getChangedVertexes(): array
    {
        $changedVertexes = [];

        foreach ($this->edges as $edge){
            $changedVertexes = array_merge($changedVertexes, $edge->getChangedVertexes());
        }

        return $changedVertexes;
    }

    /**
     * @return Edge[]
     */
    public function getDeletedEdges()
    {
        $deletedEdges = [];

        foreach ($this->edges as $edge){
            if ($edge->wasDelete()){
                $deletedEdges[] = $edge;
            }
        }

        return $deletedEdges;
    }

    /**
     * @return Vertex[]
     */
    public function getDeletedVertexes()
    {
        $deletedVertexes = [];

        foreach ($this->edges as $edge){
            $deletedVertexes = array_merge($deletedVertexes, $edge->getDeletedVertexes());
        }

        return $deletedVertexes;
    }

    /**
     * @param int $edgeId
     * @throws NotFoundException
     */
    public function deleteEdge(int $edgeId)
    {
        foreach ($this->edges as $edge){
            if ($edge->getId() === $edgeId){
                $edge->delete();
                return;
            }
        }

        throw new NotFoundException('Данная вершина не найдена');
    }

    /**
     * @param int $vertexId
     * @throws NotFoundException
     */
    public function deleteVertex(int $vertexId)
    {
        foreach ($this->edges as $edge){
            foreach ($edge->getVertexes() as $vertex){
                if ($vertex->getId() === $vertexId){
                    $this->deletePairOfVertex($edge->getId(), $vertex);
                    return;
                }
            }
        }

        throw new NotFoundException('Такого ребра не существует');
    }

    private function deletePairOfVertex(int $edgeId ,Vertex $vertex){
        $vertex->delete();
        $edge = $vertex->getEdge();

        foreach ($edge->getVertexes() as $vertex){
            if ($vertex->getEdge()->getId() === $edgeId){
                $vertex->delete();
            }
        }
    }
}