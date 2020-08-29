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

    public function save(Graph $graph)
    {
        if ($graph->needToSave()){
            $graphObject = new \app\infrastructure\persistance\Graph();
            $graphObject->name = $graph->getName();
            $graphObject->user_id = $graph->getUser();
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
        $oldFirstVertexId = -1;
        $vertex = null;

        foreach ($results as $result){

            if ($result['first_vertex_id'] !== $oldFirstVertexId && $vertex == null){
                $vertex = new \app\domain\entities\graph\Vertex($result['name'], $result['id']);
            }

            if ($this->vertexExistsOnGraph($graph, $vertex)){
                continue;
            }

            if ($result['fist_vertex_id'] === $oldFirstVertexId && $oldFirstVertexId !== null){
                continue;
            }

            if ($result['first_vertex_id'] === null){
                $graph->addVertex($vertex);
                $oldFirstVertexId = $result['first_vertex_id'];
                continue;
            }

            $edges = $this->getEdgesOfVertex($results, $result['id'], $graph);
            foreach ($edges as $edge){
                $vertex->addEdge($edge);
            }

            $graph->addVertex($vertex);

            $oldFirstVertexId = $result['first_vertex_id'];
        }
    }

    private function getVertex(array $results, int $vertexId, Graph $graph): \app\domain\entities\graph\Vertex
    {
        foreach ($results as $result){
            if ($result['id'] === $vertexId){
                $edges = $this->getEdgesOfVertex($results, $vertexId, $graph);
                $vertex = new \app\domain\entities\graph\Vertex($result['name'], $vertexId, $edges);

                if (!$this->vertexExistsOnGraph($graph, $vertex)){
                    $graph->addVertex($vertex);
                }
                return $vertex;
            }
        }
    }

    private function getEdgesOfVertex(array $results, int $vertexId, Graph $graph)
    {
        $edges = [];

        foreach ($results as $result){
            if ($result['first_vertex_id'] === $vertexId){
                $vertex = $this->getVertex($results, $result['second_vertex_id'], $graph);
                $edge = new \app\domain\entities\graph\Edge($result['weight'], $vertex, $result['edge_id']);
                $edges[] = $edge;
            }
        }

        return $edges;
    }

    private function vertexExistsOnGraph(Graph $graph, \app\domain\entities\graph\Vertex $vertex): bool
    {
        $vertexes = $graph->getVertexes();
        foreach ($vertexes as $vertexObject){
            if ($vertex->getId() === $vertexObject->getId()){
                return true;
            }
        }

        return false;
    }
}