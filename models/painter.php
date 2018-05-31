<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string $usertype
 * @property string $photo
 * @property string $birthday
 * @property string $country
 * @property string $languages
 * @property string $fbpage
 * @property string $vkpage
 * @property string $inpage
 * @property int $percent
 * @property int $state
 * @property int $role
 * @property int $rate
 * @property int $balance
 */
class painter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at', 'percent', 'state', 'role', 'rate', 'balance'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['name', 'auth_key'], 'string', 'max' => 32],
            [['usertype', 'photo', 'languages', 'fbpage', 'vkpage', 'inpage'], 'string', 'max' => 250],
            [['birthday', 'country'], 'string', 'max' => 80],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
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
            'percent' => 'Percent',
            'state' => 'State',
            'role' => 'Role',
            'rate' => 'Rate',
            'balance' => 'Balance',
        ];
    }
}
