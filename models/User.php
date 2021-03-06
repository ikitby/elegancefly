<?php

namespace app\models;

use function foo\func;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_NOT_ACTIVE = 0;
    
    /* User events */

    const EVENT_USER_REGISTERED = 'New user registered';
    const EVENT_USER_NEW_PURCHASE = 'New user purchase';
    //const EVENT_USER_NEW_PROJECT = 'New user project';

    public function init()
    {
        $this->on(User::EVENT_USER_REGISTERED, [$this, 'SendAdminMail']);
        $this->on(User::EVENT_USER_NEW_PURCHASE, [$this, 'SendAuthorMail']);
        //$this->on(User::EVENT_USER_NEW_PROJECT, [$this, 'SendNewProjectAdminMail']);
    }

    // ==================== Send email about new register User
    public function SendAdminMail($event)
    {
        $user = $event->sender;
        $mail_admins = User::getUsersByIds(User::UsersByPermission('canReceiveSiteMail'));

        $messages = [];
        foreach ($mail_admins as $mailadmin) {
            $messages[] = Yii::$app->mailer->compose('userEventEmail', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (отправлено роботом).'])
                ->setTo($mailadmin->email)
                ->setSubject('Новый пользователь на '.Yii::$app->name);
        }
        Yii::$app->mailer->sendMultiple($messages);
    }


    /* User events */
/*
    public $username;
    public $status;
*/
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function getAllRating($user_id)
    {
        $ratingcount = Ratings::find()->where(['rateuser_id' => $user_id])->count();
        $rating = Ratings::find()->where(['rateuser_id' => $user_id])->sum('rating');
        
        $result = array([
            'count' => $ratingcount,
            'ratingall' => $rating,
            'rating' => ($ratingcount > 0 ) ? round($rating/$ratingcount, 1) : 0
        ]);
        
    }

    public function rules()
    {
        return [

            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            [['username', 'email'], 'trim'],
            [['status', 'created_at', 'updated_at', 'percent', 'state', 'sales', 'country'], 'integer'],
            [['rate'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['name', 'auth_key'], 'string', 'max' => 32],
            [['photo', 'role', 'languages', 'fbpage', 'vkpage', 'inpage'], 'string', 'max' => 250],
            [['birthday', 'country'], 'string', 'max' => 80],
            [['username','email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['fbpage', 'vkpage', 'inpage'], 'url', 'defaultScheme' => 'http'],
            [['fbpage'], 'match', 'pattern' => '/^(https?:\/\/)?(www)\.(facebook)\.(com)([\/\w \.-]*)*\/?$/i'],
            [['inpage'], 'match', 'pattern' => '/^(https?:\/\/)?(www)\.(instagram)\.(com)([\/\w \.-]*)*\/?$/i'],
            [['vkpage'], 'match', 'pattern' => '/^(https?:\/\/)?(vk)\.(com)([\/\w \.-]*)*\/?$/i'],
            [['youtubepage'], 'match', 'pattern' => '/^(https?:\/\/)?(www)\.(youtube)\.(com).*?$/i'],
            [['tumblrpage'], 'match', 'pattern' => '/^(https?:\/\/)?(www)\.(tumblr)\.(com).*?$/i'],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_NOT_ACTIVE]],
            ['status', 'default', 'value' => self::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'name' => 'Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'usertype' => 'Usertype',
            'photo' => 'Photo',
            'birthday' => 'Birthday',
            'country' => 'Country',
            'languages' => 'Languages',
            'fbpage' => 'Fbpage',
            'vkpage' => 'Vkpage',
            'inpage' => 'Inpage',
            'youtubepage' => 'youtube',
            'tu mblrpage' => 'tumblr',
            //'percent' => 'Percent',
            'state' => 'State',
            'role' => 'Role',
            'rate' => 'Rate',
            'sales' => 'Sales',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    public function getRateProjects()
    {
        return $this->hasMany(Products::className(), ['id' => 'project_id'])
            ->viaTable('ratings', ['user_id' => 'id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['action_user' => 'id']);
    }

    public static function getUsersByIds ($user_ids)
    {
        $users = User::find()
            ->where(['id' => $user_ids, 'status' => self::STATUS_ACTIVE])
            ->all();
        return $users;
    }
/*
    //get user mails by id
    public static function getUserMailsById ($user_ids)
    {
        $email = "";
        $counter = 0;
        $total = count($user_ids);

        foreach ($user_ids as $id) {
            $counter++;
            if($counter == $total){
                $email .= '\''.static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE])->email.'\'';
            }
            else{
                $email .= '\''.static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE])->email.'\', ';
            }
        };
        return $email;
    }
*/

    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['user_id' => 'id']);
    }

    public function getUserEvents()
    {
        return $this->hasMany(Userevent::className(), ['user_id' => 'id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['rateuser_id' => 'id']);
    }

    public function getStatuses()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'usertype']);
    }

    public function getUserCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country']);
    }

    /* cart */

    public function getCartItems()
    {
        return $this->hasMany(Cart::className(), ['buyer_id' => 'id']);
    }

    public function getUserLevel()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::find()->where('username=:email OR email=:email', [":email"=>$email])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findByPasswordResetToken($token)
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByActivateKey($token)
    {
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_NOT_ACTIVE,
        ]);
    }

    public static function Can($permission, $itemid = '')
    {
        return Yii::$app->authManager->checkAccess(Yii::$app->user->id, $permission, $itemid);
    }

    public static function UsersByPermission($permission)
    {
        return Yii::$app->authManager->getUserIdsByRole('canReceiveSiteMail');
    }

    public static function Is($user_id, $role)
    {
        $user_roles = User::Roles($user_id);
        foreach ($user_roles as $user_role) {
            if (strtolower($user_role->name) == strtolower($role)) {
                return true;
                break;
            };
        }
        return false;
    }

    public static function Roles($user_id) {
        $roles = Yii::$app->authManager->getRolesByUser($user_id);
        return $roles;
    }

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();

    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function saveImage($filename)
    {
        $this->photo = $filename;
        $this->save(false);
    }

    public function getUserRating($id)
    {
        $ratingcount = Ratings::find()->where(['rateuser_id' => $id])->count();
        $rating = Ratings::find()->where(['rateuser_id' => $id])->sum('rating');

        $result = array([
                'count' => $ratingcount,
                'ratingall' => $rating,
                'rating' => ($ratingcount > 0 ) ? round($rating/$ratingcount, 1) : 0
            ]);
        return $result;
    }

    public static function getUserProjectsCount($id)
    {
        return $countProjects = Products::find()->where(['user_id' => $id, 'state' => 1, 'deleted' => 0])->count();
    }

    public static function getUsersCount($usertype){
        $userlevel = ($usertype) ? $usertype : 'User';

        $count = User::find()
            ->joinWith('userLevel')
            ->where(['auth_assignment.item_name' => $userlevel])
            ->count();

        return $count;
    }

    public static function getPercent($userId, $render = 0)
    {
        $user = User::findOne($userId);
        $userPercent = ($user->percent <= 0 || $user->percent > 100) ? 50 : $user->percent;
        $userPercent = ($render == 1) ? $userPercent.'%' : $userPercent/100;

        return $userPercent;
    }

    public static function getById($id) {
        return User::findOne($id);
    }

}