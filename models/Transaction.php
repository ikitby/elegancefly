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
            [[ 'action_user', 'prod_id', 'type', 'source_payment'], 'integer'],
            [[ 'amount', 'c_balance'], 'double'],
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
            'source_payment' => 'Source of payment',
            'amount' => 'Amount',
            'c_balance' => 'c_balance',
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

    public function getActionUser()
    {
        return $this->hasOne(User::className(), ['id' => 'action_user']);
    }


    public function getSourcePayment()
    {
        return $this->hasOne(User::className(), ['id' => 'source_payment']);
    }

    public function getactionProd()
    {
        return $this->hasOne(Products::className(), ['id' => 'prod_id']);
    }


    //Проверка есть ли в базе продукт купленый пользователем и может ли его купить пользователь еще раз
    public static function checkPurchase($user_id, $prod_id, $type = 1)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => $type])->count();
        return (empty($count)) ? true : false;
    }

    //Проверка есть ли в базе продукт купленый пользователем и может ли его скачать
    public static function allowDownload($user_id, $prod_id)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => 0])->count();
        return (!empty($count)) ? true : false;
    }


    //получаем сколько продаж у пользователя
    public static function getUserSales($user_id)
    {
        $sales = Transaction::find()->where(['action_user' => $user_id, 'type' => 1])->count();
        return $sales;
    }


    //получаем все продажи пользователя
    public static function getUserTransactions($user_id)
    {
        $transactions = Transaction::find()->where(['action_user' => $user_id])->orderBy(['created_at' => SORT_DESC])->all();
        return $transactions;
    }


    //Устанавливаем количество продаж пользователю в его запись для сортировок
    public static function setUserSales($user_id)
    {
        $user  = User::findOne($user_id);
        $user->sales = Transaction::getUserSales($user_id);
        return $user->save(false);
    }


    //Сколько продаж проекта
    public static function getProjectSelling($project_id)
    {
        return Transaction::find()->where(['prod_id' => $project_id])->count();
    }


}
