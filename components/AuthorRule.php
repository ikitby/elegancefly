<?php

namespace app\components;

use yii\rbac\Rule;


class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        dump($user);
        dump($item);
        dump($params);
        die();

        dump(isset($params['post']) ? $params['post']->user_id == $user : false);die();
        //return isset($params['post']) ? $params['post']->user_id == $user : false;
    }
}