<?php

namespace app\components;


use Yii;
use yii\rbac\Rule;


class AuthorAdminRule extends Rule
{
    public $name = 'userGroup';

    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            //dump(Yii::$app->user->identity); die();
            $group = Yii::$app->user->identity->group;

            if ($item->name === 'admin') {
                return $group == 1;
            } elseif ($item->name === 'painter') {
                return $group == 1 || $group == 2;
            }
        }
        return false;
    }
}
