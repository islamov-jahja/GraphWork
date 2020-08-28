<?php


namespace app\infrastructure\repositories\graph;


use app\domain\entities\graph\Graph;
use app\domain\exceptions\NotFoundException;
use app\domain\repositories\IGraphRepository;
use app\infrastructure\persistance\Edge;
use app\infrastructure\persistance\Vertex;
use yii\db\ActiveQuery;

class GraphRepository implements IGraphRepository
{

    public function save(Graph $graph)
    {
        if ($graph->needToSave()){
            $graphObject = new \app\infrastructure\persistance\Graph();
            $graphObject->name = $graph->getName();
            $graphObject->save();
        }

        foreach ($graph->getVertexes() as $vertex){
            if ($vertex->needToSave()){
                $vertexObject = new Vertex();
                $vertexObject->name = $vertex->getName();
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

    public function getById(int $graphId): Graph
    {
        $graphObject = \app\infrastructure\persistance\Graph::findOne(['id' => $graphId]);
        if ($graphObject == null){
            throw new NotFoundException('Данного графа не существует');
        }

        $graph = new Graph($graphObject->name, $graphObject->user_id, $graphId);
        $results = Vertex::find()
            ->leftJoin(Edge::class, 'vertex.id = edge.first_vertex_id')
            ->where(['vertex.graph_id' => $graphId]);

        $this->saveVertexes($results, $graph);
        return $graph;
    }

    private function saveVertexes(ActiveQuery $results, Graph )
    {

    }
}