<?php


namespace app\controllers;

use yii\rest\Controller;

class ErrorController extends Controller
{
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        return ['error' => $exception->getMessage()];
    }
}