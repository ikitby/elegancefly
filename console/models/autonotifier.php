<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 21.09.2018
 * Time: 18:34
 */

namespace app\console\models;

use app\models\Transaction;
use app\models\User;
use Yii;

class autonotifier
{

    const USER_CAN_CASHE = true;

    public static function getCasheUsers() {
        //Получение списка Пользователей с их адресами и счетом у которых балвнс выше чем указаный минимально для вывода налички
        $query = User::find();
        $subQuery = Transaction::find()
            ->select(['action_user, SUM(amount) AS transaction_amount'])
            ->groupBy('action_user');
        $query->leftJoin([
            'orderSum'=>$subQuery
        ], 'orderSum.action_user = id')
            ->select(['id', 'name', 'username', 'email', 'orderSum.transaction_amount'])
            ->asArray()
            ->where(['>', 'orderSum.transaction_amount', Yii::$app->params['minLimitCasheMoney']])
            ->all();

        return $query->all();

    }
}