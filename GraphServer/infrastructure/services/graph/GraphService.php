<?php


namespace app\infrastructure\services\graph;


use app\domain\entities\graph\Graph;
use app\domain\exceptions\AccessIsDeniedException;
use app\domain\exceptions\NotFoundException;
use app\domain\repositories\IGraphRepository;
use app\domain\repositories\IUserRepository;
use app\domain\services\IGraphService;
use app\infrastructure\helpers\AuthHelper;
use app\infrastructure\services\graph\dto\GraphDTO;

class GraphService implements IGraphService
{
    private $graphRepository;
    private $userRepository;

    public function __construct(IGraphRepository $graphRepository, IUserRepository $userRepository)
    {
        $this->graphRepository = $graphRepository;
        $this->userRepository = $userRepository;
    }

    public function create(GraphDTO $graphDTO)
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

    public function addEdge()
    {
        // TODO: Implement addEdge() method.
    }

    public function deleteEdge(int $edgeId)
    {

    }

    public function addVertex()
    {

    }

    public function deleteVertex(int $vertexId)
    {
        // TODO: Implement deleteVertex() method.
    }

    public function changeWeightOfVertex(int $vertexId, int $weight)
    {
        // TODO: Implement changeWeightOfVertex() method.
    }
}