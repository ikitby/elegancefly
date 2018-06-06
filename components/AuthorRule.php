<?php

namespace app\components;

use yii\rbac\Rule;


class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        dump(isset($params['model']) ? $params['model']->user_id == $user : false);
        die();
        
        return isset($params['model']) ? $params['model']->user_id == $user : false; //ен отдает true когда надо.
    }
}

