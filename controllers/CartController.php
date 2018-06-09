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
use Yii;

class CartController extends AppController
{

    public function actionIndex()
    {

        $cartprod = Cart::find()
            ->where(['buyer_id' => Yii::$app->user->id])
            ->with(['cartproduct', 'buyer'])
            ->all();

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
        $cart_id = Yii::$app->request->get('id');

        if ($cart_id) {
            $cart = new Cart();
            $cart->delFromCart($cart_id);
            return true;
        }
    }

    public function actionAdd()
    {
        $prod_id = Yii::$app->request->get('id');

        if ($prod_id) {
            $cart = new Cart();
            $cart->addToCart($prod_id);
            return true;
        }
    }

}