<?php

namespace app\modules\admin\controllers;

class PagesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
