<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
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

    /**
     *
     */
    public function init()
    {
        $this->css = static::getCss();
        $this->js = static::getJs();
    }

    /**
     * @return array
     */
    public static function getCss()
    {
        return [
            'css/site.css?v='.mt_rand(1000,10000),
        ];
    }

    /**
     * @return array
     */
    public static function getJs()
    {
        return [

        ];
    }
}
