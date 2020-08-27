<?php


namespace app\controllers;

use app\domain\services\IUserService;
use app\infrastructure\services\user\dto\LoginDTO;
use app\infrastructure\services\user\dto\UserSignupDTO;
use app\middleware\Bearer;
use yii\rest\Controller;

class UserController extends Controller
{
    private $userService;

    public function __construct($id, $module, IUserService $userService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
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

    public function actionSignup()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $user = new UserSignupDTO($params['name'] ?? null, $params['password'] ?? null, $params['email'] ?? null);

        if ($user->validate()){
            $this->userService->signup($user);
            return \Yii::$app->response->setStatusCode(201);
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $user->getErrors()];
        }
    }

    public function actionLogin()
    {
        $params = \Yii::$app->getRequest()->getBodyParams();
        $loginDto = new LoginDTO($params['email'] ?? null, $params['password'] ?? null);

        if ($loginDto->validate()) {
            $accessToken = $this->userService->login($loginDto);
            return ['accessToken' => $accessToken];
        }else{
            return \Yii::$app->response->setStatusCode(400)->data = ['errors' => $loginDto->getErrors()];
        }
    }

    public function actionLogout()
    {
        $this->userService->logout();
        return \Yii::$app->response->setStatusCode(200);
    }
}