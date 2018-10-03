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
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\db\Exception;
use yii\helpers\Json;
//use yii\web\BadRequestHttpException;
//use PayPal\Api\CreditCard;
//use PayPal\Exception\PaypalConnectionException;
use yii\web\NotFoundHttpException;

class CartController extends AppController
{

    public function actionIndex()
    {
        $cartprod = $this->getCartItems();


        if (empty($cartprod)) {
            Yii::$app->session->setFlash('warning', 'Your basket is empty. Put the goods in the basket.');
            return $this->redirect(['/catalog']);}

        return $this->render('index', [
            'cartprod'      => $cartprod,
        ]);
    }


    public function actionExtCheckout()
    {
        $gateway = Yii::$app->request->get('gateway');
        $success = Yii::$app->request->get('success');

        if ($gateway) {
            $cartprod = $this->getCartItems();
            if (empty($cartprod)) {
                Yii::$app->session->setFlash('warning', 'Your basket is empty. Put the goods in the basket.');
                return $this->redirect(['/catalog']); //Если корзина пуста - бросаем пользвоателя на главку каталога
            }

            return $this->render('checkout_' . $gateway, [
                'cartprod' => $cartprod,
                'gateway' => $gateway
            ]);
        } elseif ($success) {

            $apiPaypal = Yii::$app->cm;
            $apiContext = new apiContext(
                new OAuthTokenCredential($apiPaypal->client_id, $apiPaypal->client_secret)
            );

            $paymentId = Yii::$app->request->get('paymentId');
            $token = Yii::$app->request->get('token');
            $PayerID = Yii::$app->request->get('PayerID');
            $cid = Yii::$app->request->get('cid');

            if (!isset($success) && $paymentId && $PayerID)
            {
                throw new NotFoundHttpException('The requested page does not exist.');
            }

            $payment = Payment::get($paymentId, $apiContext);
            $execute = new PaymentExecution();
            $execute->setPayerId($PayerID);

            try
            {
                $result = $payment->execute($execute, $apiContext);

                //Блок раздачи плюшек всем авторам при оплате кокупки paypal.

                $transaction_id = UuidHelper::uuid();
                $cartItems = $this->getCartItems();
                $cart_result = 'Успешная оплата. Спасибо!';

                foreach ($cartItems as $item) {

                    if ($item->basket_uniq_id != $cid || !$item->basket_uniq_id) {
                        $cart_result = 'Внутренняя ошибка корзины. Либо попытка подмены транзакции!';
                        continue;
                    }

                    //----- Обработка стоимости
                    $itemprice = $item->price; //Полная цена товара
                    $autorProcent = $itemprice*0.5;
                    //----- Обработка стоимости

                    //--------------------- Start Trasnsaction --------------------
                    $paymenttransaction = Transaction::getDb()->beginTransaction();
                    try {

                    //--------------------- Start Trasnsaction --------------------
                        //Минусуем стоимость работы у покупателя (В данном случае ни чего не минусуем)
                        $current_balance = $this->getUserBalance(Yii::$app->user->id); //баланс художника

                        $transaction = new Transaction();
                        $transaction->action_id = $transaction_id;
                        $transaction->action_purse = 'Paypal';
                        $transaction->action_user = Yii::$app->user->id;
                        $transaction->source_payment = $item->seller_id;
                        $transaction->amount = 0;
                        $transaction->c_balance = $current_balance; //пополняем запись текущего баланска в транзакции ни чего не меняя
                        $transaction->type = 0; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса)
                        $transaction->prod_id = $item->product_id;
                        $transaction->status = '1';
                        $transaction->save();

                        //Отдаем денежку автору за работу
                        $current_balance = $this->getUserBalance($item->seller_id); //баланс художника
                        $transaction = new Transaction();
                        $transaction->action_id = $transaction_id;
                        $transaction->action_user = $item->seller_id;
                        $transaction->source_payment = Yii::$app->user->id;
                        $transaction->amount = $autorProcent;
                        $transaction->c_balance = $current_balance + $autorProcent; //пополняем запись текущего баланска в транзакции
                        $transaction->type = 1; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса)
                        $transaction->prod_id = $item->product_id;
                        $transaction->status = '1';
                        $transaction->save();

//--------------------- End Trasnsaction --------------------
                        $paymenttransaction->commit();
                    } catch (\Exception $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    } catch (\Throwable $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    }
//--------------------- End Trasnsaction --------------------
                    //Удаляем товар из корзины
                    $cartItem = Cart::findOne($item->id);
                    $cartItem->delete();

                    //Обновляем счетчик продаж пользователя в его аккаунте
                    Transaction::setUserSales($item->seller_id);

                }

                Yii::$app->session->setFlash('success', $cart_result); //записываем в сессию сообщение для результата
                return $this->redirect(['/catalog']);

            } catch (Exception $e) {
                throw new NotFoundHttpException($e);
            }

        }
        throw new NotFoundHttpException('The requested page does not exist.');
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
            return Json::encode('И таки шо, Зяма, мы тут делаем?') ;
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
                if (Transaction::checkPurchase($item->seller_id, $item->product_id,0)) {
                    if ($item->buyer_id != Yii::$app->user->id) die('Подмена пользователя');
                    if (Products::checkLimit($item->product_id) != true) //Если лимит ищерпан - удаляем из корзины товар
                    {
                        $cartItem = Cart::findOne($item->id);
                        $cartItem->delete();
                        Return json_encode('Вы не можете приобрести! Product id: #'.$item->product_id.' Лимит продаж исчерпан
Товар удален из корзины. Проверьте результат и продолжите покупку
');
                    }

                    //----- Обработка стоимости
                    $itemprice = $item->price; //Полная цена товара
                    $autorProcent = $itemprice*0.5;
                    //dump($autorProcent);
                    //----- Обработка стоимости

//--------------------- Start Trasnsaction --------------------
                    $paymenttransaction = Transaction::getDb()->beginTransaction();
                    try {
//--------------------- Start Trasnsaction --------------------
                    //Минусуем стоимость работы у покупателя
                    $current_balance = $this->getUserBalance(Yii::$app->user->id); //баланс художника

                    $transaction = new Transaction();
                    $transaction->action_id = $transaction_id;
                    $transaction->action_purse = 'PAC';
                    $transaction->action_user = Yii::$app->user->id;
                    $transaction->source_payment = $item->seller_id;
                    $transaction->amount = -$itemprice;
                    $transaction->c_balance = $current_balance-$itemprice; //пополняем запись текущего баланска в транзакции
                    $transaction->type = 0; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса)
                    $transaction->prod_id = $item->product_id;
                    $transaction->status = '1';
                    $transaction->save();

                    //Отдаем денежку автору за работу
                    $current_balance = $this->getUserBalance($item->seller_id); //баланс художника
                    $transaction = new Transaction();
                    $transaction->action_id = $transaction_id;
                    $transaction->action_user = $item->seller_id;
                    $transaction->source_payment = Yii::$app->user->id;
                    $transaction->amount = $autorProcent;
                    $transaction->c_balance = $current_balance+$autorProcent; //пополняем запись текущего баланска в транзакции
                    $transaction->type = 1; //(0 - Покупка, 1 - Продажа, 3 - Пополнение баланса)
                    $transaction->prod_id = $item->product_id;
                    $transaction->status = '1';
                    $transaction->save();
//--------------------- End Trasnsaction --------------------
                        $paymenttransaction->commit();
                    } catch(\Exception $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    } catch(\Throwable $e) {
                        $paymenttransaction->rollBack();
                        throw $e;
                    }
//--------------------- End Trasnsaction --------------------
                    //Обновляем счетчик продаж пользователя в его аккаунте
                    Transaction::setUserSales($item->seller_id);

                    //Удаляем товар из корзины

                    $cartItem = Cart::findOne($item->id);
                    $cartItem->delete();

                } else {
                    return 'No';
                }
            }
            Return json_encode('Успешная транзакция!');
        } else {
            return json_encode('У вас недостаточно средств на счете!');
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
/*
    public function actionMakePayments()
    { // or whatever yours is called

        $card = new PayPalCreditCard;
        $card->setType('visa')
            ->setNumber('4111111111111111')
            ->setExpireMonth('06')
            ->setExpireYear('2018')
            ->setCvv2('782')
            ->setFirstName('Richie')
            ->setLastName('Richardson');

        try {
            $card->create(Yii::$app->ppm->getContext());
            // ...and for debugging purposes
            echo '<pre>';
            var_dump('Success scenario');
            echo $card;
        } catch (PayPalConnectionException) {
            echo '<pre>';
            var_dump('Failure scenario');
            //echo $e;
        }
    }
*/
}