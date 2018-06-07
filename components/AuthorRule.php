<?php

namespace app\components;

use app\models\Products;
use yii;
use yii\rbac\Rule;


class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {

        if (empty($params['model'])) {
            $params['model'] = $this->findModel($params); //если массив параметрой пуст - принудительно передаем ему массив параметров с id проекта
        }

        return isset($params['model']) ? $params['model']->user_id == $user : false; //ен отдает true когда надо.
    }

    private function findModel($params)
    {
        if (($model = Products::findOne($params)) !== null) {
            return $model;
        }
    }


}


