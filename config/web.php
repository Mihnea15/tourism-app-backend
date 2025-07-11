<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [

                // Info: The above configuration is optional.
                // Without the above configuration, the API would only recognize
                // application/x-www-form-urlencoded and multipart/form-data input formats.
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableSession' => false,
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->format == \yii\web\Response::FORMAT_JSON) {
                    // Setăm headerele CORS
                    $response->headers->set('Access-Control-Allow-Origin', '*');
                    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                    $response->headers->set('Access-Control-Allow-Headers', '*');
                    $response->headers->set('Access-Control-Allow-Credentials', 'true');

                    // Formatăm răspunsul pentru a se potrivi cu ce așteaptă frontend-ul
                    if ($response->data !== null && !$response->isSuccessful) {
                        $response->data = [
                            'success' => false,
                            'data' => $response->data
                        ];
                    } else {
                        $response->data = [
                            'success' => true,
                            'data' => $response->data
                        ];
                    }
                }
                if (Yii::$app->request->isOptions) {
                    $response->statusCode = 204;
                    $response->data = null;
                    return false;
                }
            },
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api-city', 'api-business', 'user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <action>' => 'options',
                        'GET get-favourites' => 'get-favourites',
                        'POST add-favourite' => 'add-favourite',
                        'GET get-user-data' => 'get-user-data',
                        'POST register' => 'register',
                        'POST login' => 'login',
                        'POST upload-user-photo' => 'upload-user-photo',
                        'POST update-password' => 'update-password',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];

return $config;
