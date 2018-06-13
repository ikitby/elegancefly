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
        if ($this->getUserBalance() - Cart::getCartsumm() >= 0){

            $cartItems = $this->getCartItems();


            foreach ($cartItems as $item)
            {
                $transaction = new Transaction();
                $transaction->action_id = 1;
                $transaction->action_user = $item->seller_id;
                $transaction->amount = $item->price;
                $transaction->prod_id = $item->product_id;
                $transaction->save();
            }

            Return 'OK!';
        } else {
            return 'У вас недостаточно средств на счете!';
        }
    }

    protected function getUserBalance()
    {
        return Transaction::getUserBalance(Yii::$app->user->id);
    }

    private function getCartItems()
    {
        return Cart::find()
                ->where(['buyer_id' => Yii::$app->user->id])
                ->with(['cartproduct', 'buyer'])
                ->all();
    }


}