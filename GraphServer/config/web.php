<?php

use app\domain\repositories\IGraphRepository;
use app\domain\repositories\IUserRepository;
use app\domain\services\IGraphService;
use app\domain\services\IUserService;
use app\infrastructure\persistance\User;
use app\infrastructure\repositories\graph\GraphRepository;
use app\infrastructure\repositories\user\UserRepository;
use app\infrastructure\services\graph\GraphService;
use app\infrastructure\services\user\UserService;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'container' => [
        'singletons' => [
            IGraphService::class => ['class' => GraphService::class],
            IUserService::class => ['class' => UserService::class],
            IUserRepository::class => ['class' => UserRepository::class],
            IGraphRepository::class => ['class' => GraphRepository::class],
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gYObLZUTP5wqCRrzeGbuSDV2ZWSPcVgq',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST signup' => 'user/signup',
                'POST login' => 'user/login',
                'POST logout' => 'user/logout',
                'POST graph' => 'graph/create',
                'GET graph/limit/<limit:\d+>/page/<page:\d+>' => 'graph/getall',
                'GET graph/<id:\d+>' => 'graph/get',
                'DELETE graph/<id:\d+>' => 'graph/delete',
                'POST graph/<id:\d+>/vertex' => 'graph/createvertex',
                'DELETE graph/<id:\d+>/vertex/<vertexId:\d+>' => 'graph/deletevertex',
                'POST graph/<id:\d+>/edge' => 'graph/createedge',
                'DELETE graph/<id:\d+>/edge/<edgeId:\d+>' => 'graph/deleteedge',
                'PUT graph/<id:\d+>/edge/<edgeId:\d+>/weight/<weight:\d+>' => 'graph/setweight',
                'GET graph/<id:\d+>/firstVertex/<firstVertexId:\d+>/secondVertex/<secondVertexId:\d+>' => 'graph/shortway',
                'GET swagger' => 'swagger/swagger'
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;
