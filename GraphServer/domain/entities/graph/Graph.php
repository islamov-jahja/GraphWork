<?php


namespace app\domain\entities\graph;


use app\domain\entities\User;
use app\domain\exceptions\NotFoundException;

class Graph
{
    private $id;
    private $name;
    private $vertexes;
    private $user;
    private $needToDelete;
    private $needToSave;

    /**
     * @param string $name
     * @param User|null $user
     * @param int|null $id
     * @param Vertex[] $vertexes
     */
    public function __construct(string $name, ?User $user, ?int $id = null, array $vertexes = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->vertexes = $vertexes;
        $this->user = $user;
        $this->needToDelete = false;
        $this->needToSave = false;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
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
            foreach ($vertex->getEdges() as $edge){
                if ($edge->getId() === $edgeId){
                    $edge->setWeight($weight);
                    return;
                }
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

        foreach ($this->vertexes as $edge){
            $deletedEdges = array_merge($deletedEdges, $edge->getDeletedEdges());
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
            foreach ($vertex->getEdges() as $edge){
                if ($edge->getId() === $edgeId){
                    $this->deletePairOfEdges($vertex->getId(), $edge);
                    return;
                }
            }
        }

        throw new NotFoundException('Такого ребра не существует');
    }

    private function deletePairOfEdges(int $vertexId , Edge $edge){
        $edge->delete();
        $vertex = $edge->getVertex();

        foreach ($vertex->getEdges() as $edge){
            if ($edge->getVertex()->getId() === $vertexId){
                $edge->delete();
                return;
            }
        }
    }
}