<?php

namespace app\modules\admin\models;

use app\models\AuthAssignment;
use app\models\Transaction;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $name
 * @property string $photo
 * @property string $user_phones
 * @property string $user_skype
 * @property string $user_telegramm
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property int $status
 * @property string $updated
 * @property string $created
 * @property int $role
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'role'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['username', 'email', 'name', 'user_phones', 'user_skype', 'user_telegramm', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['photo'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'email' => 'Email',
            'name' => 'Имя',
            'photo' => 'Фото',
            'user_phones' => 'User Phones',
            'user_skype' => 'User Skype',
            'user_telegramm' => 'User Telegramm',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'status' => 'Status',
            'updated_at' => 'Updated_at',
            'created_at' => 'Created_at',
            'role' => 'Role',
            'orderAmount'=>Yii::t('app', 'Баланс')
        ];
    }

    public function getOrderAmount()
    {
        return $this
            ->hasMany(Transaction::className(), ['action_user'=>'id'])
            ->sum('amount');
    }

    public function getUserLevel()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }




}
