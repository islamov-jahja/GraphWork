<?php


namespace app\commands;


use Workerman\Worker;
use yii\console\Controller;

class SocketController extends Controller
{
    public function actionStart()
    {
        $ws_worker = new Worker('websocket://0.0.0.0:2346');

        $ws_worker->count = 4;

        $ws_worker->onConnect = function ($connection) {
            echo "New connection\n";
        };

        $ws_worker->onMessage = function ($connection, $data) {
            $connection->send('Hello ' . $data);
        };

        $ws_worker->onClose = function ($connection) {
            echo "Connection closed\n";
        };

        Worker::runAll();
    }
}