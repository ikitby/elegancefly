<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'en',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
            //'layout' => 'admin',
        ],
        'rbac' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'app\models\User',
                    'idField' => 'id',
                    'usernameField' => 'username',
                ],
            ],
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/main.php',

        ]

    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            //'rbac/*',
            'show*',
            'catalog/index',
            'catalog/view*',
            'catalog/category*',
            'catalog/tema',
            'catalog/painter*',
            'catalog/tag',
            'painters*',
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'cache' => 'cache',
            //'defaultRoles' => ['user'],
        ],

        'cm' => [ // bad abbreviation of "PaypalMoney"; not sustainable long-term
            'class' => 'app\components\CashMoney', // note: this has to correspond with the newly created folder, else you'd get a ReflectionError
            'isProduction' => false,
            // Next up, we set the public parameters of the class
            'client_id' => 'AcNgvESyw-HTyZ7cwAk2E7CMl2Qyqt99PUHOCqabZdpQKDvwza3v5ySpOTnBbfGGcJkDdol9_LRCvKa5',
            'client_secret' => 'ELFAsnIMM1_CsPZTVEzC0MktzrtcPY81-DMh0C_RxAH9Z4Pu-fZVuIcBdLKCIeEOkrEGRg2fUOYtAECm',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mRtgfA1_ru5V5-83LwS2pcqqqa6AnHHh',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        */
        'user' => [
            'identityClass' => 'mdm\admin\models\User',
            'loginUrl' => ['login'],
            'enableAutoLogin' => true,
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],
        */
        'mailer' => [
            'useFileTransport' => true,
            'messageConfig' => [
                'charset' => 'UTF-8',
            ],
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.ikit.by',
                'username' => 'info@ikit.by',
                'password' => 'HfeK3ksoU',
                'port' => '25',
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

        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                //'signup' => 'site/signup',

                '/catalog/ajaxfile/' => '/catalog/ajaxfile',
                'catalog/' => 'catalog/index',
                'catalog/<id:\d+>' => 'catalog/view',
                'catalog/tag/<alias:\w+>' => 'catalog/tag',
                'catalog/tema/<alias:\w+>' => 'catalog/tema',
                'profile/' => 'profile/index',
                'profile' => 'profile/',
                'profile/payments' => 'profile/payments/',
                'cart' => 'cart/',
                'painters/index' => 'painters',
                'painters/<alias:\w+>' => 'painters/user',
                'painters' => 'painters/',
                'catalog/create' => 'catalog/create',
                'catalog/update' => 'catalog/update',
                'catalog/rate' => 'catalog/rate',
                'catalog/show' => 'catalog/show',
                'catalog/show<ProductsSearch[category]:\d+>' => 'catalog/show',
                'catalog/delete/<id:\d+>' => 'catalog/delete',
                'catalog/<catalias:\w+>/<id:\d+>' => 'catalog/category',
                'catalog/<catalias:\w+>' => 'catalog/category',
                'cart/ext-checkout/<gateway:\w+>' => 'cart/ext-checkout',
                'catalog/show/<painter:\w+>' => 'catalog/search',
                '/catalog/painter/<painter:\w+>' => 'catalog/painter',
                '/profile/updateproject/<id:\d+>' => '/profile/updateproject',

                //'users/<user:\w+>' => 'users',
                '<action>'=>'site/<action>',
            ],

        ],

        'formatter' => [
            'dateFormat' => 'yyyy.MM.dd',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
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
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
