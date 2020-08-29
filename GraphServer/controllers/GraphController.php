<?php


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
            'except' => ['delete', 'create', 'createvertex', 'deletevertex', 'createedge', 'deleteedge']
        ];

        $behaviors['verbFilter'] = [
            'class' => AccessFilter::class,
            'except' => ['create']
        ];

        return $behaviors;
    }

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

    public function actionDelete(int $id)
    {
        $this->graphService->delete($id);
    }

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

    public function actionDeletevertex(int $id, int $vertexId)
    {
        $this->graphService->deleteVertex($vertexId, $id);
        return \Yii::$app->response->setStatusCode(200);
    }

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

    public function actionDeleteedge(int $id, int $edgeId)
    {
        $this->graphService->deleteEdge($edgeId, $id);
    }
}