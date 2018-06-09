<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 06.04.2018
 * Time: 15:56
 */

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;

class AppController extends Controller
{
    public function init()
    {
        // Проверяем роль пользователя и если она не соответствует той, которая отмечена у него - меняем отметку
        if (!Yii::$app->user->isGuest) {
            $user = User::findOne(Yii::$app->user->id);
            $userrole = $user->role;
            $usergroups = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            foreach ($usergroups as $usergroup) {
                $group = $usergroup->name;
            }
            if ($userrole !== $group) {
                $user->role = $group;
                $user->save(false);
            }
        }
        // Проверяем роль пользователя и если она не соответствует той, которая отмечена у него - меняем отметку

    }

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