<?php


namespace app\tests\unit\graph;


use app\domain\entities\graph\Edge;
use app\domain\entities\graph\Graph;
use app\domain\entities\graph\Vertex;
use app\infrastructure\helpers\GraphManager;
use Codeception\Test\Unit;

class GraphManagerTest extends Unit
{
    public function testFindingShortWay()
    {
        $graph = $this->getGraph();
        $shortWay = GraphManager::getShortWayFromFirstToSecondVertex($graph, 1, 4);
        expect($shortWay['way'])->equals([1, 2, 4]);
    }

    private function getGraph(): Graph
    {
        $graph = new Graph('тестовый граф', null, 1);

        $vertex1 = new Vertex('вершина1', 1);
        $vertex2 = new Vertex('вершина2', 2);
        $vertex3 = new Vertex('вершина3', 3);
        $vertex4 = new Vertex('вершина4', 4);
        $vertex5 = new Vertex('вершина5', 5);

        $edge1 = new Edge(3, $vertex2, 1);
        $edge2 = new Edge(6, $vertex5, 2);
        $vertex1->addOneSidedEdge($edge1);
        $vertex1->addOneSidedEdge($edge2);

        $edge3 = new Edge(2, $vertex1, 3);
        $edge4 = new Edge(8, $vertex3, 4);
        $edge5 = new Edge(4, $vertex4, 5);
        $vertex2->addOneSidedEdge($edge3);
        $vertex2->addOneSidedEdge($edge4);
        $vertex2->addOneSidedEdge($edge5);

        $edge6 = new Edge(9, $vertex2, 6);
        $edge7 = new Edge(2, $vertex4, 7);
        $vertex3->addOneSidedEdge($edge6);
        $vertex3->addOneSidedEdge($edge7);

        $edge8 = new Edge(7, $vertex3, 8);
        $edge9 = new Edge(3, $vertex2, 9);
        $edge10 = new Edge(1, $vertex5, 10);
        $vertex4->addOneSidedEdge($edge8);
        $vertex4->addOneSidedEdge($edge9);
        $vertex4->addOneSidedEdge($edge10);

        $edge11 = new Edge(7, $vertex2, 11);
        $edge12 = new Edge(2, $vertex4, 12);
        $edge13 = new Edge(4, $vertex1, 13);
        $vertex5->addOneSidedEdge($edge11);
        $vertex5->addOneSidedEdge($edge12);
        $vertex5->addOneSidedEdge($edge13);

        $graph->addVertex($vertex1);
        $graph->addVertex($vertex2);
        $graph->addVertex($vertex3);
        $graph->addVertex($vertex4);
        $graph->addVertex($vertex5);

        return $graph;
    }
}