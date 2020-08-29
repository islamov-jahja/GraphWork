<?php


namespace app\infrastructure\repositories\graph;


use app\domain\entities\graph\Graph;
use app\domain\exceptions\NotFoundException;
use app\domain\repositories\IGraphRepository;
use app\infrastructure\persistance\Edge;
use app\infrastructure\persistance\Vertex;
use Codeception\PHPUnit\Constraint\Page;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class GraphRepository implements IGraphRepository
{

    /**
     * @param int|null $userId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getWithoutVertexesFilteredBy(?int $userId, int $limit, int $offset): array
    {
        $graphObjects = \app\infrastructure\persistance\Graph::find()
            ->select('*')
            ->where('user_id is null OR user_id = :userId', ['userId' => $userId])
            ->orderBy('id')
            ->limit($limit)
            ->offset($offset)
            ->all();

        $graphs = [];
        foreach ($graphObjects as $graphObject){
            $graphs[] = new Graph($graphObject->name, $graphObject->user_id, $graphObject->id);
        }

        return $graphs;
    }

    public function save(Graph $graph)
    {
        if ($graph->needToSave()){
            $graphObject = new \app\infrastructure\persistance\Graph();
            $graphObject->name = $graph->getName();
            $graphObject->user_id = $graph->getUserId();
            $graphObject->save();
        }

        foreach ($graph->getVertexes() as $vertex){
            if ($vertex->needToSave()){
                $vertexObject = new Vertex();
                $vertexObject->name = $vertex->getName();
                $vertexObject->graph_id = $graph->getId();
                $vertexObject->save();
            }
        }
        
        foreach ($graph->getVertexes() as $vertex){
            foreach ($vertex->getEdges() as $edge) {
                if ($edge->needToSave()){
                    $edgeObject = new Edge();
                    $edgeObject->weight = $edge->getWeight();
                    $edgeObject->first_vertex_id = $vertex->getId();
                    $edgeObject->second_vertex_id = $edge->getVertex()->getId();
                    $edgeObject->save();
                }
            }
        }
    }

    public function changeWeightOfEdges(Graph $graph)
    {
        $edgesToChange = $graph->getEdgesToChange();

        foreach ($edgesToChange as $edge) {
            Edge::updateAll(['weight' => $edge->getWeight()], ['id' => $edge->getId()]);
        }
    }

    public function delete(Graph $graph)
    {
        if ($graph->needToDelete()){
            \app\infrastructure\persistance\Graph::deleteAll(['id' => $graph->getId()]);
            return;
        }

        $edgesToDelete = $graph->getEdgesToDelete();

        foreach ($edgesToDelete as $edge){
            Edge::deleteAll(['id' => $edge->getId()]);
        }

        $vertexesToDelete = $graph->getVertexesToDelete();

        foreach ($vertexesToDelete as $vertex){
            Vertex::deleteAll(['id' => $vertex->getId()]);
        }
    }

    /**
     * @param int $graphId
     * @return Graph
     * @throws NotFoundException
     */
    public function getById(int $graphId): Graph
    {
        $graphObject = \app\infrastructure\persistance\Graph::findOne(['id' => $graphId]);
        if ($graphObject == null){
            throw new NotFoundException('Данного графа не существует');
        }

        $graph = new Graph($graphObject->name, $graphObject->user_id, $graphId);
        $results = (new \yii\db\Query())
            ->select([
                '"edge".id as edgeId',
                '"edge".first_vertex_id',
                '"edge".second_vertex_id',
                '"edge".weight',
                '"vertex".id',
                '"vertex".name'
            ])
            ->from('"vertex"')
            ->leftJoin('"edge"', '"edge".first_vertex_id = "vertex".id')
            ->where(['"vertex".graph_id' => $graphId])
            ->all();

        $this->saveVertexes($results, $graph);

        return $graph;
    }

    /**
     * @param ActiveRecord[] $results
     * @param Graph $graph
     */
    private function saveVertexes(array $results, Graph $graph)
    {
        foreach ($results as $result){
            $vertex = $this->getVertex($result['id'], $graph);

            if ($result['first_vertex_id'] !== null){
                $edge = new \app\domain\entities\graph\Edge($result['weight'],
                        $this->getVertex($result['second_vertex_id'], $graph), $result['edgeId']);
                $vertex->addOneSidedEdge($edge);
            }
        }
    }

    public function getVertex(int $vertexId, Graph $graph){
        try{
            return $graph->getVertexById($vertexId);
        }catch (NotFoundException $notFoundException){
            $vertexObject = Vertex::findOne(['id' => $vertexId]);
            $vertex = new \app\domain\entities\graph\Vertex($vertexObject['name'], $vertexId);
            $graph->addVertex($vertex);
            return $vertex;
        }
    }
}