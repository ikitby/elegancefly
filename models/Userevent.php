<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "userevent".
 *
 * @property int $id
 * @property string $event_user
 * @property string $event_time
 * @property string $event_type
 * @property string $event_desc
 * @property int $event_progress
 */
class Userevent extends \yii\db\ActiveRecord
{
    /**
     *

    //-----------------------------------------------------------------

    $userEvent = new Userevent();
    $userEvent->setLog(Yii::$app->user->id,'system','Cистемное сообщение в логе','1');

    //-----------------------------------------------------------------

     *
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userevent';
    }


    const USER_EVENT_LOG = 'New user event log';
    const USER_EVENT_CAN_RECIVE_MONEY = 'User can recive money from PAC';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_user', 'event_time', 'event_desc'], 'required'],
            [['event_user', 'event_progress'], 'integer'],
            [['event_time'], 'safe'],
            [['event_type'], 'string', 'max' => 50],
            [['event_desc'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_user' => 'Event User',
            'event_time' => 'Event Time',
            'event_type' => 'Event Type',
            'event_desc' => 'Event Desc',
            'event_progress' => 'Event Progress',
        ];
    }
/*
    public function init()
    {
        $this->on(Userevent::USER_EVENT_LOG, [$this, 'SetEventLog']);
    }
*/
    public function setLog($userid, $type = 'system', $descr, $progress = 1) {
        $this->on(Userevent::USER_EVENT_LOG, [$this, 'SetEventLog'],[
            'user_id' => $userid,
            'type' => $type,
            'descr' => $descr,
            'progress' => $progress
        ]);
        $this->trigger($this::USER_EVENT_LOG);
    }

    public function UsercCanReciveMoney($userid, $ilmitmoney = 50) {

        $this->on(Userevent::USER_EVENT_CAN_RECIVE_MONEY, [$this, 'NoteUserCash'],[
            'user_id' => $userid,
            'type' => 'rmoney',
            'descr' => 'Congratulation! You can cash a personal account!',
            'progress' => 0
        ]);
        $this->trigger($this::USER_EVENT_CAN_RECIVE_MONEY);
    }

    // ==================== Send email about user cashe
    protected function NoteUserCash($event)
    {
        $user_id = $event->data['user_id'];
        $limitCashe = Yii::$app->params['minLimitCasheMoney'];
        $userbalance = Transaction::getUserBalance($user_id);
        $serNotify = '';

        $users = User::find()
            ->joinWith('Transaction')
            ->where(['auth_assignment.item_name' => 'Painter'])
            //->orWhere(['auth_assignment.item_name' => 'Creator'])
            ->andWhere(['status' => '10'])
            //->limit(10)
            ->all();
            dump($users);
        if ($userbalance >= $limitCashe) {
            dump($userbalance);
        }

        $userevent = new Userevent();
        $userevent->event_user = $user_id;
        $userevent->event_time = date('Y-m-d H:i:s');
        $userevent->event_type = $event->data['type'];
        $userevent->event_desc = $event->data['descr'];
        $userevent->event_progress = $event->data['progress'];

        $userevent->save();

        /*
        $mail_admins = User::getUsersByIds(User::UsersByPermission('canReceiveSiteMail'));

        $messages = [];
        foreach ($mail_admins as $mailadmin) {
            $messages[] = Yii::$app->mailer->compose('userEventEmail', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (отправлено роботом).'])
                ->setTo($mailadmin->email)
                ->setSubject('Новый пользователь на '.Yii::$app->name);
        }
        Yii::$app->mailer->sendMultiple($messages);
        */
    }


    // ==================== Send email about new register User
    protected function SetEventLog($event)
    {
        $userevent = new Userevent();
        $userevent->event_user = $event->data['user_id'];
        $userevent->event_time = date('Y-m-d H:i:s');
        $userevent->event_type = $event->data['type'];
        $userevent->event_desc = $event->data['descr'];
        $userevent->event_progress = $event->data['progress'];

        $userevent->save();

        /*
        $mail_admins = User::getUsersByIds(User::UsersByPermission('canReceiveSiteMail'));

        $messages = [];
        foreach ($mail_admins as $mailadmin) {
            $messages[] = Yii::$app->mailer->compose('userEventEmail', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (отправлено роботом).'])
                ->setTo($mailadmin->email)
                ->setSubject('Новый пользователь на '.Yii::$app->name);
        }
        Yii::$app->mailer->sendMultiple($messages);
        */
    }

    public function getEventUser()
    {
        return $this->hasOne(User::className(), ['id' => 'event_user']);
    }

}
