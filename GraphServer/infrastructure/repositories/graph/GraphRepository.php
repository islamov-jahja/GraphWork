<?php


namespace app\infrastructure\repositories\graph;


use app\domain\entities\graph\Graph;
use app\domain\repositories\IGraphRepository;
use app\infrastructure\persistance\Edge;

class GraphRepository implements IGraphRepository
{

    public function save(Graph $graph)
    {
        if ($graph->needToSave()){
            $graphObject = new \app\infrastructure\persistance\Graph();
            $graphObject->name = $graph->getName();
            $graphObject->save();
        }

        foreach ($graph->getVertexes() as $edge){
            if ($edge->needToSave()){
                $edgeObject = new Edge();
                $edgeObject->n
            }
        }
    }

    public function changeStateless(Graph $graph)
    {
        // TODO: Implement changeStateless() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}