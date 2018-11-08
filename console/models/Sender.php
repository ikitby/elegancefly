<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 07.11.2018
 * Time: 15:42
 */

namespace app\console\models;

use Yii;

class Sender
{
    public static function run($users) {

        $messages = [];

        foreach ($users as $user) {
            $messages[] = Yii::$app->mailer
                ->compose('userCanCasheMail', ['user' => $user, 'balance' => $user['transaction_amount']])
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($user['email'])
                ->setSubject('Поздравляем! Вы можете вывести деньги со своего персонального счете PAC');
        }
        //var_dump($messages);
        $result = Yii::$app->mailer->sendMultiple($messages);

        Return $result;
    }
}