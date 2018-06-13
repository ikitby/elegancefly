<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'action_user', 'amount'], 'required'],
            [['action_id', 'action_user', 'amount'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action_id' => 'Action ID',
            'action_user' => 'Seller User',
            'amount' => 'Amount',
            'prod_id' => 'Product',
            'created_at' => 'Created At',
        ];
    }

    //Получаем баланс пользователя по ID

    public static function getUserBalance($user_id)
    {
        $amount = Transaction::find()->where(['action_user' => $user_id])->sum('amount');
        return (!empty($amount)) ? $amount : 0;
    }




}
