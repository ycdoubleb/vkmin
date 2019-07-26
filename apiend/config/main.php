<?php

$params = array_merge(
        require __DIR__ . '/../../common/config/params.php', require __DIR__ . '/../../common/config/params-local.php', require __DIR__ . '/params.php', require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-apiend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'apiend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-apiend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            //'identityCookie' => ['name' => '_identity-apiend', 'httpOnly' => true],
            'enableSession' => false,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the apiend
            'name' => 'advanced-apiend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>s' => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        /* 微信公众号SDK */
        'wechat' => [
            'class' => 'jianyan\easywechat\Wechat',
            'userOptions' => [], // 用户身份类参数
            'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
            'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
        ],
    ],
    'params' => $params,
    'modules' => [
        'v1' => [
            'class' => 'apiend\modules\v1\Module',
        ],
    ],
];
