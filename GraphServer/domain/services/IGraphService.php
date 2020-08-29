<?php


namespace app\domain\services;


use app\domain\entities\graph\Graph;
use app\infrastructure\services\graph\dto\EdgeDTO;
use app\infrastructure\services\graph\dto\GraphDTO;
use app\infrastructure\services\graph\dto\VertexDTO;

interface IGraphService
{
    public function addGraph(GraphDTO $graphDTO): void;
    public function delete(int $graphId): void;
    public function deleteEdge(int $edgeId, int $graphId): void;
    public function addVertex(VertexDTO $vertexDTO): void;
    public function deleteVertex(int $vertexId, int $graphId): void;
    public function changeWeightOfEdge(int $edgeId, int $graphId, int $weight): void;
    public function addEdge(EdgeDTO $edgeDTO): void;

    public function get(int $id): Graph;

    /**
     * @param int $id
     * @param int $firstVertexId
     * @param int $secondVertexId
     * @return int[]
     */
    public function getShortWay(int $id, int $firstVertexId, int $secondVertexId): array;

    /**
     * @param int $limit
     * @param int $page
     * @return Graph[]
     */
    public function getAll(int $limit, int $page): array;
}