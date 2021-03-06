<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "cart".
 *
 * @property int $id
 * @property int $product_id
 * @property int $buyer_id
 * @property int $qty
 * @property string $name
 * @property string $img
 * @property int $price
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'buyer_id', 'name', 'img'], 'required'],
            [['product_id', 'buyer_id', 'qty'], 'integer'],
            [[ 'price'], 'double'],
            [['name', 'img'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'buyer_id' => 'Buyer ID',
            'qty' => 'Qty',
            'name' => 'Name',
            'img' => 'Img',
            'price' => 'Price',
        ];
    }


    public static function getCartsumm()
    {
        return Cart::find()
            ->where(['buyer_id' => Yii::$app->user->id])
            ->sum('price');
    }

    public static function getCartsummWS()
    {
        $summ = 0;
        //Получаем все продукты в корзине
        $cartprod = Cart::find()
            ->where(['buyer_id' => Yii::$app->user->id])
            ->With('cartproduct')
            ->all();
        //перебираем их проверяя скидки и считаем общую сумму
        foreach ($cartprod as $product) {
            $price = Promotions::getSalePrice($product->cartproduct);
            if ($price) {
                $summ = $summ+$price['price'];
            } else {
                $summ = $summ + $product->cartproduct->price;
            }

        }

        return round($summ, 2);
    }

    public static function getCartCount()
    {
        return Cart::find()
            ->where(['buyer_id' => Yii::$app->user->id])
            ->count();
    }

    public function getBuyer()
        {
            return $this->hasOne(User::className(), ['id' => 'buyer_id']);
        }
/*
    public function getCatprod()
    {
        return $this->hasOne(Catprod::className(), ['id' => 'buyer_id']);
    }
*/
    public function getCartproduct()
        {
            return $this->hasOne(Products::className(), ['id' => 'product_id']);
        }


    public function addToCart($prod_id)
    {
        $product = Products::findOne($prod_id);
        if (empty($product)) return false;
        if (Products::checkLimit($prod_id) != true) return false;

        $user_id = Yii::$app->user->id;
        $incart = Cart::find()->where(['product_id' => $prod_id, 'buyer_id' => $user_id])->one();
        if ($incart) return true;

        $photos = json::decode($product->photos);
        $this->product_id = $product->id;
        $this->seller_id = $product->user_id;
        $this->buyer_id = $user_id;
        $this->qty = 1;
        $this->name = $product->title;
        $this->img = $photos[0]['filepath'].'100_100_'.$photos[0]['filename'];
        $this->price = $product->price;
        return $this->save();
    }

    public function delFromCart($cart_id)
    {
        $user_id = Yii::$app->user->id;
        $cartitem = Cart::find()->where(['id' => $cart_id, 'buyer_id' => $user_id])->one();
        return $cartitem->delete();

    }

}
