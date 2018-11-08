<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 06.11.2018
 * Time: 13:18
 */

namespace app\console;

use app\console\models\autonotifier;
use app\console\models\Sender;
use Yii;


class CachenotifyController extends \yii\console\Controller
{

    public function actionSend()
    {

        $users = autonotifier::getCasheUsers();

        $result = Sender::run($users);

        print $result;

    }

}