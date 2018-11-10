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
    const NOTE_SENDED = 1;
    const NOTE_CLOSE = 0;

    public static function run($users) {

        $messages = [];

        foreach ($users as $user) {

            $userName = ($user['name']) ? $user['name'] : $user['username'];

            $iSuserEvent = Userevent::find()->where([
                'event_user' => $user['id'],
                'event_type' => 'cachenotify',
                'event_progress' => Sender::NOTE_SENDED
            ])->max('event_time');
            if (!$iSuserEvent) {
                //-----------------------------------------------------------------

                $userEvent = new Userevent();
                $userEvent->setLog($user['id'], 'cachenotify', 'Уведомление <span class="nusername">' . $userName . '</span> о возможности обналички <span class="label label-info">'.$user['transaction_amount'].'$</span>', Sender::NOTE_SENDED);

                //-----------------------------------------------------------------

                $messages[] = Yii::$app->mailer
                    ->compose('userCanCasheMail', ['user' => $user, 'balance' => $user['transaction_amount']])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($user['email'])
                    ->setSubject('Поздравляем! Вы можете вывести деньги со своего персонального счете PAC');
            }

        }
        //var_dump($messages);
        $result = Yii::$app->mailer->sendMultiple($messages);



        Return $result;
    }
}