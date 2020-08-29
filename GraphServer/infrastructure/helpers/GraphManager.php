<?php


namespace app\infrastructure\helpers;


use app\domain\entities\graph\Edge;
use app\domain\entities\graph\Graph;

class GraphManager
{
    public static function getShortWayFromFirstToSecondVertex(Graph $graph, $firstVertexId, $secondVertexId): array
    {
        if ($firstVertexId === $secondVertexId){
            return [1];
        }

        $processingArray = self::getInitedProcessingArray($graph, $firstVertexId);
        return self::getWay($graph, $processingArray, $secondVertexId);
    }

    private static function getInitedProcessingArray(Graph $graph, int $firstVertexId): array
    {
        $firstVertex = $graph->getVertexById($firstVertexId);
        $processingArray = [];
        $processingArray['vertexes'][] = [
            'id' => $firstVertexId,
            'weight' => 0,
            'way' => [$firstVertexId]
        ];

        $processingArray['way'] = [];

        $vertexes = $graph->getVertexes();

        foreach ($vertexes as $vertex){
            $processingArray['toVertex'][$vertex->getId()]['weight'] = null;
            $processingArray['toVertex'][$vertex->getId()]['way'] = null;
        }

        foreach ($firstVertex->getEdges() as $edge){
            $processingArray['toVertex'][$edge->getVertex()->getId()]['weight'] = $edge->getWeight();
            $processingArray['toVertex'][$edge->getVertex()->getId()]['way'] = [$firstVertex->getId()];
        }

        return $processingArray;
    }

    private static function getWay(Graph $graph, array $processingArray, $secondVertexId): array
    {
        while(count($graph->getVertexes()) !== count($processingArray['vertexes'])){
            $nextVertex = self::getNextVertexWithMinWeight($processingArray);
            $processingArray['vertexes'][] = $nextVertex;
            $nextVertexObject = $graph->getVertexById($nextVertex['id']);
            self::updateWayIfWeightUnder($processingArray, $nextVertexObject->getEdges(), $nextVertex);
        }

        foreach ($processingArray['toVertex'] as $id => $body){
            if ($id === $secondVertexId){
                $body['way'][] = $secondVertexId;
                return $body;
            }
        }
    }

    /**
     * @param array $processingArray
     * @param Edge[] $edges
     * @param array $vertex
     */
    private static function updateWayIfWeightUnder(array& $processingArray, array $edges, array $vertex): void
    {
        foreach ($edges as $edge) {
            $weight = $processingArray['toVertex'][$edge->getVertex()->getId()]['weight'];

            if ($weight > ($edge->getWeight() + $vertex['weight']) || $weight === null) {
                $processingArray['toVertex'][$edge->getVertex()->getId()]['weight'] = ($edge->getWeight() + $vertex['weight']);
                $processingArray['toVertex'][$edge->getVertex()->getId()]['way'] = $vertex['way'];
            }
        }
    }

    private static function getNextVertexWithMinWeight(array $processingArray): array
    {
        $minWeight = null;
        $responseBody = [];

        foreach ($processingArray['toVertex'] as $id => $body){
            if (($body['weight'] <= $minWeight || $minWeight === null) &&
                !in_array($id, array_column($processingArray['vertexes'], 'id')) &&
                $body['weight'] !== null
            )
            {
                $minWeight = $body['weight'];
                $responseBody = $body;
                $responseBody['id'] = $id;
            }
        }

        $responseBody['way'][] = $responseBody['id'];
        return $responseBody;
    }
}