<?php
return [
    //'language' => 'ru-RU',
    //'sourceLanguage' => 'en-US',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        /*'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '&nbsp;',
            'thousandSeparator' => ' ',
            'language' => 'ru-RU',
        ],*/
    ],
];
