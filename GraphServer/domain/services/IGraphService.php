<?php


namespace app\domain\services;


use app\infrastructure\services\graph\dto\GraphDTO;

interface IGraphService
{
    public function create(GraphDTO $graphDTO);
    public function delete(int $graphId);
    public function addEdge();
    public function deleteEdge(int $edgeId);
    public function addVertex();
    public function deleteVertex(int $vertexId);
    public function changeWeightOfVertex(int $vertexId, int $weight);
}