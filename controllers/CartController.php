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


    }

    public function actionAdd()
    {
        $prod_id = Yii::$app->request->get('id');

        $product = Products::findOne($prod_id);

        if (empty($product)) return false;


        print $product->title;
        die();
    }

}