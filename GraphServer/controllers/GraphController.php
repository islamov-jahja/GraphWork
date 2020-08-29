<?php

/**
 * @OA\Info(title="Test work graph", version="0.1")
 * @OA\Server(url="http://tattelekomgraph/GraphServer/")
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header"
 * )
 */

namespace app\controllers;


use app\domain\services\IGraphService;
use app\infrastructure\services\graph\dto\EdgeDTO;
use app\infrastructure\services\graph\dto\GraphDTO;
use app\infrastructure\services\graph\dto\VertexDTO;
use app\middleware\AccessFilter;
use app\middleware\Bearer;
use yii\rest\Controller;

class GraphController extends Controller
{
    private $graphService;

    public function __construct($id, $module, IGraphService $graphService, $config = [])
    {
        $this->graphService = $graphService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => Bearer::class,
            'except' => ['delete', 'create', 'createvertex', 'deletevertex', 'createedge', 'deleteedge', 'setweight', 'get', 'shortway', 'getall']
        ];

        $behaviors['verbFilter'] = [
            'class' => AccessFilter::class,
            'except' => ['create', 'getall']
        ];

        return $behaviors;
    }

    /**
     * @OA\Post (
     *   tags={"Graph"},
     *   path="/graph",
     *   description="create graph",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(
     *          property="name",
     *          type="string",
     *      )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="created graph"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="bad request"
     *   )
     * )
     */
    public function actionCreate()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $graphDTO = new GraphDTO($params['name'] ?? null);

        if ($graphDTO->validate()) {
            $this->graphService->addGraph($graphDTO);
            return \Yii::$app->response->setStatusCode(201);
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $graphDTO->getErrors()];
        }
    }

    /**
     * @OA\Delete (
     *   tags={"Graph"},
     *   path="/graph/{id}",
     *   description="delete graph",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="deleted"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found"
     *   )
     * )
     */
    public function actionDelete(int $id)
    {
        $this->graphService->delete($id);
    }

    /**
     * @OA\Post (
     *   tags={"Graph"},
     *   path="/graph/{id}/vertex",
     *   description="create vertex",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(
     *          property="name",
     *          type="string",
     *      )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="created"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="bad request"
     *   )
     * )
     */
    public function actionCreatevertex(int $id)
    {
        $params = \Yii::$app->request->getBodyParams();

        $vertexDTO = new VertexDTO($params['name'], $id);

        if ($vertexDTO->validate()){
            $this->graphService->addVertex($vertexDTO);
            return \Yii::$app->response->setStatusCode(201);
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $vertexDTO->getErrors()];
        }
    }

    /**
     * @OA\Delete (
     *   tags={"Graph"},
     *   path="/graph/{id}/vertex/{vertexId}",
     *   description="delete vertex",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="deleted"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found, vertex not found"
     *   )
     * )
     */
    public function actionDeletevertex(int $id, int $vertexId)
    {
        $this->graphService->deleteVertex($vertexId, $id);
        return \Yii::$app->response->setStatusCode(200);
    }

    /**
     * @OA\Post (
     *   tags={"Graph"},
     *   path="/graph/{id}/edge",
     *   description="create edge",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(
     *          property="weight",
     *          type="integer",
     *      ),
     *     @OA\Property(
     *          property="firstVertexId",
     *          type="integer",
     *      ),
     *     @OA\Property(
     *          property="secondVertexId",
     *          type="integer",
     *      )
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="created"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="bad request"
     *   )
     * )
     */
    public function actionCreateedge(int $id)
    {
        $params = \Yii::$app->request->getBodyParams();
        $edgeDTO = new EdgeDTO(
            $params['weight'] ?? null,
            $params['firstVertexId'] ?? null,
            $params['secondVertexId'] ?? null,
            $id
        );

        if ($edgeDTO->validate()){
            $this->graphService->addEdge($edgeDTO);
            return \Yii::$app->response->setStatusCode(201);
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $edgeDTO->getErrors()];
        }
    }

    /**
     * @OA\Delete (
     *   tags={"Graph"},
     *   path="/graph/{id}/edge/{edgeId}",
     *   description="delete edge",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="deleted"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found, edge not found"
     *   )
     * )
     */
    public function actionDeleteedge(int $id, int $edgeId)
    {
        $this->graphService->deleteEdge($edgeId, $id);
        return \Yii::$app->response->setStatusCode(200);
    }

    /**
     * @OA\Put (
     *   tags={"Graph"},
     *   path="/graph/{id}/edge/{edgeId}",
     *   description="change weight of edge",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *     @OA\Property(
     *          property="weight",
     *          type="integer",
     *      )
     *     )
     *   ),
     *   @OA\Response(
     *     response=202,
     *     description="weight updated"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="bad request"
     *   )
     * )
     */
    public function actionSetweight(int $id, int $edgeId, int $weight)
    {
        $this->graphService->changeWeightOfEdge($edgeId, $id, $weight);
        return \Yii::$app->response->setStatusCode(200);
    }

    /**
     * @OA\Get (
     *   tags={"Graph"},
     *   path="/graph/{id}",
     *   description="get graph",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="graph",
     *     @OA\Schema (
     *          type="object",
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found"
     *   )
     * )
     */
    public function actionGet(int $id)
    {
        $graph = $this->graphService->get($id);
        return $graph->toArray();
    }

    /**
     * @OA\Get (
     *   tags={"Graph"},
     *   path="/graph/{id}/firstVertex/{firstVertexId}/secondVertex/{secondVertexId}",
     *   description="get short way for 2 points",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="array of values",
     *     @OA\Schema (
     *          type="array",
     *          @OA\Items(type="integer")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="graph not found, vertexes not found"
     *   )
     * )
     */
    public function actionShortway(int $id, int $firstVertexId, int $secondVertexId)
    {
        return $this->graphService->getShortWay($id, $firstVertexId, $secondVertexId);
    }

    /**
     * @OA\Get (
     *   tags={"Graph"},
     *   path="/graph/limit/{limit}/page/{page}",
     *   description="get list of graphs without vertexes",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="array of graphs",
     *     @OA\Schema (
     *          type="array",
     *          @OA\Items(type="object")
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description=""
     *   )
     * )
     */
    public function actionGetall(int $limit, int $page)
    {
        $graphObjects = $this->graphService->getAll($limit, $page);
        $graphs = [];

        foreach ($graphObjects as $graphObject){
            $graphs[] = $graphObject->toArray();
        }

        return $graphs;
    }
}


