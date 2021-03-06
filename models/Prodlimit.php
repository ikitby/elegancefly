<?php

namespace app\models;

use Yii;

class Prodlimit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'limit'], 'integer'],
            [['price'], 'match', 'pattern' => '/^\d{1,3}[\,\.]{1}\d{1,2}$/i'],
            [['price'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'price' => 'Price',
            'limit' => 'Limit',
        ];
    }
}
