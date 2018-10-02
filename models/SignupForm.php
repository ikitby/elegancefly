<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Signup form
 */
class SignupForm extends Model
{

    const STATUS_ACTIVE = 10;
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 0;
    public $username;
    public $email;
    public $password;
    public $status;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_repeat'],  'filter', 'filter' => 'trim'],
            [['username'], 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['username', 'email', 'password', 'password_repeat'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User'],
            ['password', 'string', 'min' => 6, 'max' => 255],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_NOT_ACTIVE]],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],

            //['password', 'compare', 'compareAttribute' => 'password_repeat'],
        ];
    }

    public function attributeLabels()
    {
        return parent::attributeLabels();
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = mb_strtolower(Html::encode($this->username), 'UTF8');
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //$user->generatePasswordResetToken();
        if ($this->scenario === 'emailActivation') $user->generatePasswordResetToken();

        if ($user->save()) {
            $userRole = Yii::$app->authManager->getRole('user'); // Назначаем роль по умолчани. для пользователя
            Yii::$app->authManager->assign($userRole, $user->id);
            return $user;
        }
        return null;
    }

    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activtionEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (отправлено роботом).'])
            ->setTo($this->email)
            ->setSubject('Активация для '.Yii::$app->name)
            ->send();
    }

}