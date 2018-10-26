<?php

namespace app\models;

use thamtech\uuid\helpers\UuidHelper;
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

    public static function ApprowTranaction($cid)
    {
        $transaction = Transaction::find()->where(['action_id' => $cid, 'action_user' => Yii::$app->user->id])->one();

        if ($transaction) {

            $transaction->status = 1; //Меняем статус транзакции с нужным токеном для текущего пользователя
            //-----------------------------------------------------------------
            $user = User::getById(Yii::$app->user->id);
            $userName = ($user->name) ? $user->name : $user->username;
            $userEvent = new Userevent();
            $userEvent->setLog(Yii::$app->user->id, 'addfunds', '<span class="nusername">'.$userName.'</span> пополненил PAC на <span class="label label-warning">'.$transaction->amount.'$</span>', '1');

            //-----------------------------------------------------------------
            $transaction->save();

            return true;

        } else {
            return false;
        }

    }

    //Запрос количетства продаж и суммы
    public static function getSales($id)
    {
        $sales = array();

        $sales['sum'] = Transaction::find()->where(['prod_id' => $id, 'type' => 1])->sum('amount');
        $sales['count'] = Transaction::find()->where(['prod_id' => $id, 'type' => 1])->count();

        return $sales;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_id', 'action_user', 'amount', 'prod_id', 'type', 'status'], 'required'],
            [[ 'action_user', 'prod_id', 'type', 'status', 'source_payment'], 'integer'],
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
            'status' => 'Status transaction',
            'created_at' => 'Created At',
        ];
    }


    //Получаем баланс пользователя по ID
    public static function getUserBalance($user_id)
    {
        $amount = Transaction::find()->where(['action_user' => $user_id, 'status' => 1])->sum('amount');
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

    public static function SetDeposite ($gateway = 'Paypal', $count = '5')
    {
        $c_balance = Transaction::getUserBalance(Yii::$app->user->id);
        $uniq_id = UuidHelper::uuid();

        $transaction = new Transaction();
        //подготавливаем транзакцию для текущего пользователя
        $transaction->action_id         = $uniq_id;
        $transaction->action_purse      = $gateway;
        $transaction->action_user       = Yii::$app->user->id;
        $transaction->source_payment    = 0;
        $transaction->amount            = $count;
        $transaction->c_balance         = $count+$c_balance;
        $transaction->prod_id           = 0;
        $transaction->type              = 3;//Метка пополнения баланса
        $transaction->status            = 0;//статус не одобрено по умолчанию
        //сохраняем транзакцию для текущего пользователя
        $transaction->save();

        return $uniq_id;
    }

    //Проверка есть ли в базе продукт купленый пользователем и может ли его купить пользователь еще раз
    public static function checkPurchase($user_id, $prod_id, $type = 1)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => $type, 'status' => 1])->count();
        return (empty($count)) ? true : false;
    }

    //Проверка есть ли в базе продукт купленый пользователем и может ли его скачать
    public static function allowDownload($user_id, $prod_id)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => 0, 'status' => 1])->count();
        $author = Products::isAuthor($prod_id, $user_id);
        return (!empty($count) || $author) ? true : false;
    }

/*
    public function allowDownld($user_id, $prod_id)
    {
        $count = Transaction::find()->where(['action_user' => $user_id, 'prod_id' => $prod_id, 'type' => 0])->count();
        return (!empty($count)) ? true : false;
    }
*/
    //получаем сколько продаж у пользователя
    public static function getUserSales($user_id)
    {
        $sales = Transaction::find()->where(['action_user' => $user_id, 'type' => 1, 'status' => 1])->count();
        return $sales;
    }

    //получаем сколько продаж у пользователя
    public static function getProdSales($prod_id)
    {
        $sales = Transaction::find()->where(['prod_id' => $prod_id, 'type' => 0, 'status' => 1])->count();
        return $sales;
    }

    //получаем все продажи пользователя
    public static function getUserTransactions($user_id)
    {
        $transactions = Transaction::find()->where(['action_user' => $user_id, 'status' => 1])->orderBy(['created_at' => SORT_DESC])->all();
        return $transactions;
    }


    //Устанавливаем количество продаж пользователю в его запись для сортировок
    public static function setUserSales($user_id)
    {
        $user  = User::findOne($user_id);
        $user->sales = Transaction::getUserSales($user_id);
        return $user->save(false);
    }



}
