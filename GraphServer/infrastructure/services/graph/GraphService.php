<?php


namespace app\infrastructure\services\graph;


use app\domain\repositories\IGraphRepository;
use app\domain\services\IGraphService;

class GraphService implements IGraphService
{
    private $graphRepository;

    public function __construct(IGraphRepository $graphRepository)
    {
        $this->graphRepository = $graphRepository;
    }
}