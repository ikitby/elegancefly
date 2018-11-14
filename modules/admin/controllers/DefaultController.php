<?php

namespace app\modules\admin\controllers;

use app\models\Transaction;
use app\models\User;
use app\models\Userevent;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'сachereqdel' => ['POST'],
                    'сachereqrefuse' => ['POST'],
                    'сachereqappr' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * выделение прав пользователю
     */

    public function actionUserup()
    {
        $user_id = Yii::$app->request->post('id'); //id пользователя
        $event_id = Yii::$app->request->post('event_id'); //id события

        $user = User::getById($user_id);
        $userName = ($user->name) ? $user->name : $user->username;

        if (!Yii::$app->authManager->getRolesByUser($user_id)["Admin"]) {

            $userlevel = "User";

            if (Yii::$app->authManager->getRolesByUser($user_id)["User"]) {
                $userlevel = "Painter";
            }
            if (Yii::$app->authManager->getRolesByUser($user_id)["Painter"]) {
                $userlevel = "Creator";
            }

            //------------------------ Устанавливаем новый уровень пользователю ----------------

            $userRole = Yii::$app->authManager;
            $userRole->revokeAll($user_id); //Забираем все права у пользователя

            $newUserRole = $userRole->getRole($userlevel); //Устанавливаем новую роль
            $userRole->assign($newUserRole, $user_id); //Применяем ее на пользователя

            //-------------------- помечаем как обработанный запрос в событии ------------------

            $event = Userevent::find()->where(['id' => $event_id])->one();
            $event->event_progress = 1;
            $event->save();

            //------------------------- Создаем событие о смене профиля ------------------------

            $userEvent = new Userevent();
            $userEvent->setLog($user_id, 'user', 'Повышение уровня аккаунта <span class="nusername">'.$userName.'</span> до уровня '.$userlevel, '1');

            //-----------------------------------------------------------------
            //Отправим письмо пользоваотелю

            $userEvent = new Userevent();
            $userEvent->noteToUser($user_id, 'info', 'Повышение уровня аккаунта <span class="nusername">' . $userName . '</span>', 'apprUserMail', 'Поздравляем! У вас новый уровень аккаунта "' . $userlevel . '"', '1');

            //-----------------------------------------------------------------
            return 'ok';

        } else return 'no';

    }

    /*
     *
     * Отказ пользователю на поднятие профиля
     *
    */
    public function actionUserref()
    {
        $user_id = Yii::$app->request->post('id'); //id пользователя
        $event_id = Yii::$app->request->post('event_id'); //id события

        $user = User::getById($user_id);
        $userName = ($user->name) ? $user->name : $user->username;

            //-------------------- помечаем как обработанный запрос в событии ------------------

                        $event = Userevent::find()->where(['id' => $event_id])->one();
                        $event->event_progress = 1;
                        $event->save();

            //------------------------- Создаем событие о смене профиля ------------------------

            $userEvent = new Userevent();
            $userEvent->setLog($user_id, 'user', 'Отказ в повышении уровня аккаунта <span class="nusername">'.$userName.'</span>', '1');

            //-----------------------------------------------------------------
            //Отправим письмо пользоваотелю

            $userEvent = new Userevent();
            $userEvent->noteToUser($user_id, 'info', 'Отказ в повышении уровня аккаунта <span class="nusername">' . $userName . '</span>', 'refUserMail', 'Отказ в просьбе повышения уровня аккаунта до "' . $userlevel . '"', '1');

            //-----------------------------------------------------------------
            return 'ok';

    }

    /*
     * Просто удаляем событие запроса на обналичку
     */
    public function actionCachereqdel()
    {
        $user_id = Yii::$app->request->post('id'); //id пользователя
        $event_id = Yii::$app->request->post('event_id'); //id события

        $user = User::getById($user_id);
        $userName = ($user->name) ? $user->name : $user->username;

        $uevent = Userevent::findOne($event_id);
        $uevent->delete();

        Return 'ok';
    }
    /*
     * Отказываем с уведомлением
     */
    public function actionCachereqrefuse()
    {
        $user_id = Yii::$app->request->post('id'); //id пользователя
        $event_id = Yii::$app->request->post('event_id'); //id события

        $user = User::getById($user_id);
        $userName = ($user->name) ? $user->name : $user->username;

        $uevent = Userevent::findOne($event_id);
        $uevent->event_progress = 1;
        $uevent->save();

        //------------------------- Создаем событие об отказе вывода денег ------------------------

        $userEvent = new Userevent();
        $userEvent->setLog($user_id, 'info', '<span class="label label-danger">Отказ в выводе средств PAC</span> пользователю <span class="nusername">'.$userName.'</span> с уведомлением по почте', '1');

        //-----------------------------------------------------------------
        //Отправим письмо пользоваотелю
        $uevent = Userevent::findOne($event_id);
        $uevent->noteToUser($user_id, 'info', 'Отказ пользователю <span class="nusername">' . $userName . '</span> в выводе средств со счета PAC', 'refUserCacheMail', 'Отказ в выводе денег"', '1');


        Return 'ok';
    }

    /*
     * Одобряем с измеением баланса и уведомлением
     */
    public function actionCachereqappr()
    {
        $user_id = Yii::$app->request->post('id'); //id пользователя
        $event_id = Yii::$app->request->post('event_id'); //id события
        $reqamount = Yii::$app->request->post('reqamount'); //сумма списания
        $transaction_id = UuidHelper::uuid();
        $current_balance = $this->getUserBalance(Yii::$app->user->id);
        //обработки суммы превышающе баланс
        if ($reqamount > $current_balance || $reqamount == 0) return 'Запрошенная суппа превышает текущий баланс либо вы пытаетесь вывести 0$';
        $user = User::getById($user_id);
        $userName = ($user->name) ? $user->name : $user->username;

        $paymenttransaction = Transaction::getDb()->beginTransaction();

        try {
            $transaction = new Transaction();

            $transaction->action_id = $transaction_id;
            $transaction->action_purse = 'CashingOut';
            $transaction->action_user = $user_id;
            $transaction->source_payment = $user_id;
            $transaction->amount = -$reqamount;
            $transaction->c_balance = $current_balance-$reqamount; //пополняем запись текущего баланска в транзакции ни чего не меняя
            $transaction->type = 4; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса, 4 - вывод денег)
            $transaction->prod_id = 0;
            $transaction->status = '1';
            $transaction->save();

            $paymenttransaction->commit();
        } catch(\Exception $e) {
            $paymenttransaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $paymenttransaction->rollBack();
            throw $e;
        }

        //------------------------- Создаем событие о выводt денег ------------------------

        $userEvent = new Userevent();
        $userEvent->setLog($user_id, 'info', 'Вывод <span class="label label-info">'.$reqamount.'$</span> с персонального счета PAC <span class="nusername">'.$userName.'</span> с уведомлением по почте', '1');

        //---------------------------Отправим письмо пользоваотелю ------------------------

        $uevent = Userevent::findOne($event_id);
        $uevent->noteToUser($user_id, 'info', 'С вашего счета PAC были списаны средства', 'UserCasheDecrMail', 'Списание со счета', '1', ['amount' => $reqamount, 'current_balance' => $current_balance]);

        //---------------------------------------------------------------------------------

        $uevent = Userevent::findOne($event_id);
        $uevent->event_progress = 1;
        $uevent->save();

        Return 'ok';
    }

    protected function getUserBalance($user_id)
    {
        return \app\models\Transaction::getUserBalance($user_id);
    }

}
