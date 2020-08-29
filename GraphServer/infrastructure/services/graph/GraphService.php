<?php


namespace app\infrastructure\services\graph;


use app\domain\entities\graph\Edge;
use app\domain\entities\graph\Graph;
use app\domain\entities\graph\Vertex;
use app\domain\exceptions\NotFoundException;
use app\domain\repositories\IGraphRepository;
use app\domain\repositories\IUserRepository;
use app\domain\services\IGraphService;
use app\infrastructure\helpers\AuthHelper;
use app\infrastructure\services\graph\dto\EdgeDTO;
use app\infrastructure\services\graph\dto\GraphDTO;
use app\infrastructure\services\graph\dto\VertexDTO;

class GraphService implements IGraphService
{
    private $graphRepository;
    private $userRepository;

    public function __construct(IGraphRepository $graphRepository, IUserRepository $userRepository)
    {
        $this->graphRepository = $graphRepository;
        $this->userRepository = $userRepository;
    }

    public function addGraph(GraphDTO $graphDTO)
    {
        try{
            $user = AuthHelper::getAuthenticatedUser($this->userRepository);
            $graph = new Graph($graphDTO->getName(), $user->getId());
        }catch (NotFoundException $exception){
            $graph = new Graph($graphDTO->getName(), null);
        }
        $graph->save();

        $this->graphRepository->save($graph);
    }

    public function delete(int $graphId)
    {
        $graph = $this->graphRepository->getById($graphId);
        $graph->delete();
        $this->graphRepository->delete($graph);
    }

    public function deleteEdge(int $edgeId, int $graphId)
    {
        $graph = $this->graphRepository->getById($graphId);
        $graph->deleteEdge($edgeId);
        $this->graphRepository->delete($graph);
    }

    public function addVertex(VertexDTO $vertexDTO)
    {
        $graph = $this->graphRepository->getById($vertexDTO->getGraphId());
        $vertex = new Vertex($vertexDTO->getName());
        $vertex->save();
        $graph->addVertex($vertex);
        $this->graphRepository->save($graph);
    }

    public function deleteVertex(int $vertexId, int $graphId)
    {
        $graph = $this->graphRepository->getById($graphId);
        $graph->deleteVertex($vertexId);
        $this->graphRepository->delete($graph);
    }

    public function changeWeightOfEdge(int $edgeId, int $graphId, int $weight)
    {
        $graph = $this->graphRepository->getById($graphId);
        $graph->changeWeightOfEdge($edgeId, $weight);
        $this->graphRepository->changeWeightOfEdges($graph);
    }

    public function addEdge(EdgeDTO $edgeDTO)
    {
        $graph = $this->graphRepository->getById($edgeDTO->getGraphId());
        $firstVertex = $graph->getVertexById($edgeDTO->getFirstVertexId());
        $secondVertex = $graph->getVertexById($edgeDTO->getSecondVertexId());
        $edge = new Edge($edgeDTO->getWeight(), $secondVertex, null);
        $edge->save();
        $firstVertex->addDoubleSidedEdge($edge);
        $this->graphRepository->save($graph);
    }

    public function get(int $id)
    {
        //$user = AuthHelper::getAuthenticatedUser($this->userRepository);
        return $this->graphRepository->getById($id);
    }
}