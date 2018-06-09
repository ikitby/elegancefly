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
            [['product_id', 'buyer_id', 'qty', 'price'], 'integer'],
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

        $user_id = Yii::$app->user->id;
        $incart = Cart::find()->where(['product_id' => $prod_id, 'buyer_id' => $user_id])->one();
        if ($incart) return true;

        $photos = json::decode($product->photos);
        $this->product_id = $product->id;
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
