<?php

namespace app\assets;

use yii\web\AssetBundle;

class AdminAssets extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://fonts.googleapis.com/css?family=Roboto:300,400',
        'admin_assets/css/bootstrap.min.css',
        //'admin_assets/css/bootstrap-responsive.min.css',
        //'admin_assets/css/style.css',
        //'admin_assets/css/style-responsive.css',
        'admin_assets/css/bootstrap.min.css',
        'admin_assets/fonts/css/font-awesome.min.css',
        'admin_assets/css/animate.min.css',
        'admin_assets/css/custom.css',
        'admin_assets/css/maps/jquery-jvectormap-2.0.3.css',
        'admin_assets/css/icheck/flat/green.css',
        'admin_assets/css/floatexamples.css',


    ];
    public $js = [
        //'js/bootstrap.min.js',
        //'js/gnmenu.js',
        'admin_assets/js/jquery-2.2.4.min.js',
        //'admin_assets/js/jquery.min',
        'admin_assets/js/bootstrap.min.js',
        'admin_assets/js/nicescroll/jquery.nicescroll.min.js',
        'admin_assets/js/progressbar/bootstrap-progressbar.min.js',
        'admin_assets/js/icheck/icheck.min.js',
        'admin_assets/js/moment/moment.min.js',
        'admin_assets/js/datepicker/daterangepicker.js',
        'admin_assets/js/chartjs/chart.min.js',
        'admin_assets/js/sparkline/jquery.sparkline.min.js',
        'admin_assets/js/custom.js',
        'admin_assets/js/flot/jquery.flot.js',
        'admin_assets/js/flot/jquery.flot.pie.js',
        'admin_assets/js/flot/jquery.flot.orderBars.js',
        'admin_assets/js/flot/jquery.flot.time.min.js',
        'admin_assets/js/flot/date.js',
        'admin_assets/js/flot/jquery.flot.spline.js',
        'admin_assets/js/flot/jquery.flot.stack.js',
        'admin_assets/js/flot/curvedLines.js',
        'admin_assets/js/flot/jquery.flot.resize.js',
        'admin_assets/js/pace/pace.min.js',

    ];
    public $depends = [
        //'yii\modules\admin\YiiAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
    public $jsOptions = array(
        //'position' => \yii\web\View::POS_HEAD
    );
}