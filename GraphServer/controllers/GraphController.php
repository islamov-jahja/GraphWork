<?php


namespace app\controllers;


use app\domain\services\IGraphService;
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
            'except' => ['login', 'signup']
        ];

        return $behaviors;
    }

    public function actionCreate()
    {

    }
}