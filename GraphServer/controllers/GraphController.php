<?php


namespace app\controllers;


use app\domain\services\IGraphService;
use app\infrastructure\services\graph\dto\GraphDTO;
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
            'except' => ['delete']
        ];

        $behaviors['verbFilter'] = [
            'class' => AccessFilter::class,
            'except' => ['create']
        ];

        return $behaviors;
    }

    public function runAction($id, $params = [])
    {
        return parent::runAction($id, $params); // TODO: Change the autogenerated stub
    }

    public function actionCreate()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $graphDTO = new GraphDTO($params['name'] ?? null);

        if ($graphDTO->validate()) {
            $this->graphService->create($graphDTO);
            return \Yii::$app->response->setStatusCode(201);
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $graphDTO->getErrors()];
        }
    }

    public function actionDelete()
    {
        $graphId = \Yii::$app->request->getQueryParam('id');
        $this->graphService->delete($graphId);
    }

}