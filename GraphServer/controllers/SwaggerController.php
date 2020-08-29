<?php


namespace app\controllers;


use function OpenApi\scan;

class SwaggerController extends \yii\web\Controller
{
    public function actionSwagger()
    {
        $openApi = scan(__DIR__.'/../controllers');
        $file = __DIR__.'/../swagger.yaml';
        $handle = fopen($file, 'wb');
        fwrite($handle, $openApi->toYaml());
        fclose($handle);
        return file_get_contents(__DIR__.'/../documentation/index.html');
    }
}