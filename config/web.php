<?php

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
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'LJ1EhCqnSIems4ul05wKwFLcw_Zi4GzD',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'signup' => 'auth/signup',
                'request-password-reset' => 'auth/request-password-reset',
                'reset-password' => 'auth/reset-password',
                'verify-email' => 'auth/verify-email',
                'profile' => 'profile/view',
                'profile/edit' => 'profile/update',
                'dashboard' => 'dashboard/index',
                'purchases' => 'purchase/index',
                'purchase/create' => 'purchase/create',
                'purchase/view/<id:\d+>' => 'purchase/view',
                'purchase/update/<id:\d+>' => 'purchase/update',
                'purchase/delete/<id:\d+>' => 'purchase/delete',
                'purchase/delete-receipt/<id:\d+>' => 'purchase/delete-receipt',
                'sellers' => 'seller/index',
                'seller/create' => 'seller/create',
                'seller/view/<id:\d+>' => 'seller/view',
                'seller/update/<id:\d+>' => 'seller/update',
                'seller/delete/<id:\d+>' => 'seller/delete',
                'seller/create-ajax' => 'seller/create-ajax',
                'seller/get-sellers' => 'seller/get-sellers',
                'categories' => 'category/index',
                'category/create' => 'category/create',
                'category/view/<id:\d+>' => 'category/view',
                'category/update/<id:\d+>' => 'category/update',
                'category/delete/<id:\d+>' => 'category/delete',
                'products' => 'product/index',
                'product/create' => 'product/create',
                'product/view/<id:\d+>' => 'product/view',
                'product/update/<id:\d+>' => 'product/update',
                'product/delete/<id:\d+>' => 'product/delete',
                'product/create-ajax' => 'product/create-ajax',
                'product/get-by-category' => 'product/get-products-by-category',
                'buyers' => 'buyer/index',
                'buyer/create' => 'buyer/create',
                'buyer/view/<id:\d+>' => 'buyer/view',
                'buyer/update/<id:\d+>' => 'buyer/update',
                'buyer/delete/<id:\d+>' => 'buyer/delete',
                'buyer/create-ajax' => 'buyer/create-ajax',
                'claims' => 'claim/index',
                'claim/create' => 'claim/create',
                'claim/view/<id:\d+>' => 'claim/view',
                'claim/update/<id:\d+>' => 'claim/update',
                'claim/delete/<id:\d+>' => 'claim/delete',
                'claim/get-templates' => 'claim/get-templates',
                'claim/get-template-content' => 'claim/get-template-content',
                'claim/get-purchase-data' => 'claim/get-purchase-data',
                'claim/save-user-template' => 'claim/save-user-template',
                'claim/delete-user-template' => 'claim/delete-user-template',
                'claim/toggle-favorite-template' => 'claim/toggle-favorite-template',
            ],
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
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
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
