<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\models\Userevent;
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



}
