<?php

namespace app\models;

use Yii;

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

    public function getProducts()
        {
            return $this->hasOne(Products::className(), ['id' => 'product_id']);
        }

}
