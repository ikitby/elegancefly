<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 16.11.2018
 * Time: 15:49
 */

namespace app\modules\admin\models;


use app\models\Userevent;
use Yii;

class PromoUserNoifier
{

    const NOTE_SENDED = 1;

    public static function run($users, $promo)
    {

        $messages = [];
        $promoCats = $promo->getPromocats();
        $categories = Catprod::find()->select('title')->where(['id' => $promoCats])->asArray()->all();

        $iSpromoEvent = Userevent::find()->where([
            'event_object' => $promo['id'],
            'event_type' => 'promonotify',
            'event_progress' => PromoUserNoifier::NOTE_SENDED
        ])->select('id')->max('event_time');

        foreach ($users as $user) {

            if (!$iSpromoEvent) {
                $messages[] = Yii::$app->mailer
                    ->compose('PromoUserNotify', ['user' => $user, 'promo' => $promo, 'cats' => $categories])
                    ->setFrom(Yii::$app->params['supportEmail'])
                    ->setTo($user['email'])
                    ->setSubject('Уведомление об акции на сайте Elegancefly!');
            }
        }

        $result = Yii::$app->mailer->sendMultiple($messages);
        if ($result) {

            //-----------------------------------------------------------------

            $promoEvent = new Userevent();
            $promoEvent->setObjLog($promo['id'], 'promonotify', 'Рассылка акции <span class="promoname">' . $promo->action_title . '</span><span class="usersaction" title="Количество получателей"><span class="glyphicon glyphicon-user"></span> '.count($users).'</span>', PromoUserNoifier::NOTE_SENDED);

            //-----------------------------------------------------------------

            return 'ok';
        }
        return 'notsend';
    }

}