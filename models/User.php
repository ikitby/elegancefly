<?php

namespace app\models;

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
            [['status', 'created_at', 'updated_at', 'percent', 'state', 'role', 'rate', 'sales'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['name', 'auth_key'], 'string', 'max' => 32],
            [['photo', 'languages', 'fbpage', 'vkpage', 'inpage'], 'string', 'max' => 250],
            [['birthday', 'country'], 'string', 'max' => 80],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
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


    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['user_id' => 'id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['rateuser_id' => 'id']);
    }

    public function getStatuses()
    {
        return $this->hasOne(Statuses::className(), ['id' => 'usertype']);
    }

    /* cart */

    public function getCartItems()
    {
        return $this->hasMany(Cart::className(), ['buyer_id' => 'id']);
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
        return $countProjects = Products::find()->where(['user_id' => $id])->count();
    }
}