<?php


namespace app\controllers;

use app\domain\exceptions\NotFoundException;
use app\domain\exceptions\UserExistException;
use app\domain\exceptions\WrongEmailOrPasswordException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use yii\rest\Controller;

class ErrorController extends Controller
{
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception instanceof NotFoundException){
            return \Yii::$app->response->setStatusCode(404);
        }

        if ($exception instanceof UserExistException ||
            $exception instanceof  WrongEmailOrPasswordException ||
            $exception instanceof AccessDeniedException)
        {
            return \Yii::$app->response->setStatusCode(400)->data = (['error' => $exception->getMessage()]);
        }

        return ['error' => $exception->getMessage()];
    }
}