<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 07.06.2018
 * Time: 15:26
 */

namespace app\controllers;

use app\models\Products;
use app\models\Cart;
use app\models\Transaction;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\helpers\Json;

class CartController extends AppController
{

    public function actionIndex()
    {
        $cartprod = $this->getCartItems();

        if (empty($cartprod)) {return $this->redirect(['/catalog']);}

        return $this->render('index', [
            'cartprod'      => $cartprod,
        ]);
    }

    /**
     * @return bool
     */

    public function actionDel()
    {
        if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
            $cart_id = Yii::$app->request->get('id');

            if ($cart_id) {
                $cart = new Cart();
                $cart->delFromCart($cart_id); //Удаление из карты
            }

            $cartsum = Cart::getCartsumm();
            $cartcount = Cart::getCartCount();

            $result['cartsum'] = $cartsum;
            $result['cartcount'] = $cartcount;
            //Json::encode($result);

            return Json::encode($result);
        } else {
            return 'И таки шо, Зяма, мы тут делаем?';
        }

    }

    public function actionClear()
    {
        if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {

            Cart::deleteAll(['buyer_id' => Yii::$app->user->id]);

            return true;
        } else {
            return 'И таки шо, Зяма, мы тут делаем?';
        }

    }

    public function actionAdd()
    {
        if (!Yii::$app->getUser()->isGuest && Yii::$app->request->isAjax) {
            $prod_id = Yii::$app->request->get('id');
            if (!Products::allowPurchased($prod_id)) {return false;}

            if ($prod_id) {
                $cart = new Cart();
                $cart->addToCart($prod_id);

                $cartsum = Cart::getCartsumm();
                $cartcount = Cart::getCartCount();

                $result['cartsum'] = $cartsum;
                $result['cartcount'] = $cartcount;

                return Json::encode($result);
            }
        }
    }

    public function actionCheckout()
    {

        if ($this->getUserBalance(Yii::$app->user->id) - Cart::getCartsumm() >= 0){

            $cartItems = $this->getCartItems();
            $transaction_id = UuidHelper::uuid();


            foreach ($cartItems as $item)
            {
                if (Transaction::checkPurchase($item->seller_id, $item->product_id,1)) {
                    if ($item->buyer_id != Yii::$app->user->id) die('Подмена пользователя');


                    //----- Обработка стоимости
                    $itemprice = $item->price; //Полная цена товара
                    $autorProcent = $itemprice*0.5;
                    //dump($autorProcent);
                    //----- Обработка стоимости

                    //Минусуем стоимость работы у покупателя
                    $current_balance = $this->getUserBalance(Yii::$app->user->id); //баланс художника

                    $transaction = new Transaction();
                    $transaction->action_id = $transaction_id;
                    $transaction->action_user = Yii::$app->user->id;
                    $transaction->source_payment = $item->seller_id;
                    $transaction->amount = -$itemprice;
                    $transaction->c_balance = $current_balance-$itemprice; //пополняем запись текущего баланска в транзакции
                    $transaction->type = 0; //(0 - Покупка, 1 - Продажа, 2 - Пополнение баланса)
                    $transaction->prod_id = $item->product_id;
                    $transaction->save();

                    //Отдаем денежку автору за работу
                    $current_balance = $this->getUserBalance($item->seller_id); //баланс художника
                    $transaction = new Transaction();
                    $transaction->action_id = $transaction_id;
                    $transaction->action_user = $item->seller_id;
                    $transaction->source_payment = Yii::$app->user->id;
                    $transaction->amount = $autorProcent;
                    $transaction->c_balance = $current_balance+$autorProcent; //пополняем запись текущего баланска в транзакции
                    $transaction->type = 1; //(0 - Покупка, 1 - Продажа, 2 - Пополнение баланса)
                    $transaction->prod_id = $item->product_id;
                    $transaction->save();

                    //Обновляем счетчик продаж пользователя в его аккаунте
                    Transaction::setUserSales($item->seller_id);

                    //Удаляем товар из корзины

                    $cartItem = Cart::findOne($item->id);
                    $cartItem->delete();

                } else {
                    return 'No';
                }
            }



            Return 'OK!';
        } else {
            return 'У вас недостаточно средств на счете!';
        }
    }


    protected function getUserBalance($user_id)
    {
        return Transaction::getUserBalance($user_id);
    }


    private function getCartItems()
    {
        return Cart::find()
                ->where(['buyer_id' => Yii::$app->user->id])
                ->with(['cartproduct', 'buyer'])
                ->all();
    }





}