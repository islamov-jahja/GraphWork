<?php


namespace app\domain\repositories;


use app\domain\entities\graph\Vertex;
use app\domain\entities\graph\Graph;

interface IGraphRepository
{
    public function save(Graph $graph);
    public function changeWeightOfEdges(Graph $graph);
    public function delete(Graph $graph);
    public function getById(int $graphId): Graph;
}