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

    public function addDoubleSidedEdge(Edge $edge)
    {
        foreach ($this->edges as $edgeObject){
            if ($edgeObject->getVertex()->getId() === $edge->getVertex()->getId()){
                throw new \Exception('Данное ребро существует');
            }
        }

        $toVertex = $edge->getVertex();
        $secondEdge = new Edge($edge->getWeight(), $this, null);
        $secondEdge->save();
        $toVertex->addOneSidedEdge($secondEdge);

        $this->edges[] = $edge;
    }


    public function addOneSidedEdge(Edge $edge)
    {
        foreach ($this->edges as $edgeObject){
            if ($edgeObject->getVertex()->getId() === $edge->getVertex()->getId()){
                throw new \Exception('Данное ребро существует');
            }
        }

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

    public function toArray()
    {
        $vertexInArray['id'] = $this->id;
        $vertexInArray['name'] = $this->name;

        foreach ($this->edges as $edge){
            $vertexInArray['edges'][] = [
                'id' => $edge->getId(),
                'secondVertexId' => $edge->getVertex()->getId(),
                'weight' => $edge->getWeight()
            ];
        }

        return $vertexInArray;
    }
}