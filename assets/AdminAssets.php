<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'adminassets/css/site.css',
        'adminassets/css/template.css',
        'adminassets/css/main.css',
        'adminassets/css/resp.css',
    ];
    public $js = [
        //'js/jquery-2.2.4.min.js',
        'adminassets/js/jquery.scrolly.min.js',
        'adminassets/js/jquery.scrollex.min.js',
        'adminassets/js/browser.min.js',
        'adminassets/js/breakpoints.min.js',
        'adminassets/js/util.js',
        'adminassets/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );
}