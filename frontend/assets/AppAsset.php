<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

    private static function getCss()
    {
        return [
            '/css/bootstrap.min.css',
            '/css/bootstrap-icons.css',
            '/css/font-awesome.min.css',
            '//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css',
            '/css/main.css?v='.mt_rand(1000,10000),
        ];
    }

    private static function getJs()
    {
        return [
            '/js/jquery.js',
            '/js/bootstrap.bundle.min.js',
            '/js/jquery-ui.min.js',
            '/js/datepicker-ru.js',
            '/js/inputmask.js',
            '/js/jquery.inputmask.js',
            '/js/functions.js?v='.mt_rand(1000,10000),
            '/js/common.js?v='.mt_rand(1000,10000),
        ];
    }

    public function init()
    {
        $this->css = self::getCss();
        $this->js = self::getJs();
    }

}
