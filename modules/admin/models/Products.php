<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $apiary_id
 * @property string $product
 * @property string $product_desc
 * @property string $prod_image
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['apiary_id'], 'integer'],
            [['product', 'product_desc', 'prod_image'], 'required'],
            [['product', 'product_desc', 'prod_image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apiary_id' => 'Apiary ID',
            'product' => 'Product',
            'product_desc' => 'Product Desc',
            'prod_image' => 'Prod Image',
        ];
    }
}
