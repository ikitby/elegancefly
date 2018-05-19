<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 06.04.2018
 * Time: 15:56
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AppController extends Controller
{

    public function disableProfilers()
    {
        if (Yii::app()->getComponent('log')) {
            foreach (Yii::app()->getComponent('log')->routes as $route) {
                if (in_array(get_class($route), array('CProfileLogRoute', 'CWebLogRoute', 'YiiDebugToolbarRoute','DbProfileLogRoute'))) {
                    $route->enabled = false;
                }
            }
        }
    }

    public function actionDownloadFile()
    {
        //отключить профайлеры
        $this->disableProfilers();
        $file = '/path/to/file/some_file.txt';
        // отдаем файл
        Yii::app()->request->sendFile(basename($file),file_get_contents($file));
    }
}