<?php

namespace app\components;

use app\models\Products;
use yii;
use yii\rbac\Rule;


class AuthorlimitRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        //Получаем ID проекта обращающегося к редактированию проекта лимита
        $proj_id = (!empty(Yii::$app->request->post('id'))) ? Yii::$app->request->post('id') : Yii::$app->request->post('Prodlimit')['id'];

        if (empty($model)) {
            $model = $this->findModel($proj_id);
        }
        return isset($model) ? $model->user_id == $user : false;
    }

    private function findModel($proj_id)
    {
        if (($model = Products::findOne($proj_id)) !== null) {
            return $model;
        }
    }

}


