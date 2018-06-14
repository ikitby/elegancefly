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
            [['action_id', 'action_user', 'amount', 'prod_id', 'type'], 'required'],
            [['action_id', 'action_user', 'amount', 'prod_id', 'type', 'action_depend'], 'integer'],
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
            'action_depend' => 'Dependet User',
            'amount' => 'Amount',
            'type' => 'Type tranaction',
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

    public static function checkPurchase($user_id, $prod_id, $type = 1)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => $type])->count();
        return (empty($count)) ? true : false;
    }


}
