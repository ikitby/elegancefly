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
use yii\helpers\Json;

class CartController extends AppController
{

    public function actionIndex()
    {


    }

    /**
     * @return bool
     */
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