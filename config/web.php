<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\mpdf\Pdf;
use yii\bootstrap4\LinkPager as LinkPager4;
use yii\widgets\LinkPager;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$env = require __DIR__ . '/env.php';

$config = [
    'id' => 'basic',
    'name' => 'APP',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'admin', 'log-reader'],
    'language' => 'id',
    'timezone' => 'Asia/Jakarta',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu', // avaliable value 'left-menu', 'right-menu' and 'top-menu'
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'app\models\User',
                    'idField' => 'user_id'
                ]
            ],
            'menus' => [
                'assignment' => [
                    'label' => 'Grand Access' // change label
                ],
                'route' => null, // disable menu
            ],
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            'bsVersion' => 4,
            // enter optional module parameters below - only if you need to  
            // use your own export download action or custom translation 
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
        'log-reader' => [
            'class' => 'kriss\logReader\Module',
            //'as login_filter' => UserLoginFilter::class, // to use login filter
            'aliases' => [
                'Log' => '@runtime/logs/app.log',
            ],
            //'defaultTailLine' => 200,
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '17xZw-0dBnb2eZYjbMLsJ6f821U1agva',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            // 'class' => 'yii\caching\MemCache',
            // 'useMemcached' => true,
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 3600,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => $env['mail_host'],
                'username' => $env['mail_username'],
                'password' => $env['mail_password'],
                'port' => '465',
                'encryption' => 'ssl',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 100,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'exportInterval' => 100
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer',
                    'levels' => ['error'],
                    'categories' => [
                        'yii\db\*',
                        'yii\web\HttpException:500',
                    ],
                    'message' => [
                        'from' => [$params['senderEmail'] => 'AFICOS'],
                        'to' => [$params['adminEmail']],
                        'subject' => 'AFICOS : Error Log',
                    ],
                ],
            ],
        ],
        // 'session' => [
        //     'class' => 'yii\web\DbSession',
        //     // Set the following if you want to use DB component other than
        //     // default 'db'.
        //     'db' => 'mydb',
        //     // To override default session table, set the following
        //     'sessionTable' => 'my_session',
        // ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
            'defaultTimeZone' => 'Asia/Jakarta',
            'dateFormat' => 'php:d F Y',
            'datetimeFormat' => 'php:d F Y H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'Rp.',
            'locale' => 'id-ID',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 2,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ]
        ],
        // 'view' => [
        //     'theme' => [
        //         'pathMap' => [
        //             '@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
        //         ],
        //     ],
        // ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
            'cache' => 'cache',
        ],
        'assetManager' => [
            'bundles' => [
                'wbraganca\dynamicform\DynamicFormAsset' => [
                    'sourcePath' => '@app/web/js',
                    'js' => [
                        'yii2-dynamic-form.js'
                    ],
                ],
            ],
        ],
        'pdf' => [
            'class' => Pdf::class,
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'options' => ['title' => 'Krajee Report Title'],
            'cssInline' => 'tr td, tr th {
                    font-size: 12px;
                    padding: 5px;
                }',
            'methods' => [
                'SetHeader' => [''],
                'SetFooter' => ['{PAGENO}'],
            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            // tambahkan action-action yg lain di sini
            'site/*',
            'admin/*',
        ]
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            GridView::class => $params['gridConfig'],
            ExportMenu::class => $params['exportConfig'],
            LinkPager::class => LinkPager4::class,
        ],
    ],
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
