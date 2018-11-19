<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 16.11.2018
 * Time: 14:32
 */

namespace app\modules\admin\models;


class PromoUsers
{

    public static function get()
    {
        $usertypes = ['Admin','Painter','Creator'];

        return User::find()
            ->select(['id', 'name', 'username', 'email'])
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => $usertypes])
            ->asArray()
            ->all();
    }

}