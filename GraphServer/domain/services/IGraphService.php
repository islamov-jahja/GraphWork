<?php


namespace app\domain\services;


use app\infrastructure\services\graph\dto\EdgeDTO;
use app\infrastructure\services\graph\dto\GraphDTO;
use app\infrastructure\services\graph\dto\VertexDTO;

interface IGraphService
{
    public function addGraph(GraphDTO $graphDTO);
    public function delete(int $graphId);
    public function deleteEdge(int $edgeId, int $graphId);
    public function addVertex(VertexDTO $vertexDTO);
    public function deleteVertex(int $vertexId, int $graphId);
    public function changeWeightOfEdge(int $edgeId, int $graphId, int $weight);
    public function addEdge(EdgeDTO $edgeDTO);
}