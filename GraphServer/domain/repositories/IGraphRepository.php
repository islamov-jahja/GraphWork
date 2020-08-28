<?php


namespace app\domain\repositories;


use app\domain\entities\graph\Vertex;
use app\domain\entities\graph\Graph;

interface IGraphRepository
{
    public function save(Graph $graph);
    public function changeStateless(Graph $graph);
    public function delete();
}