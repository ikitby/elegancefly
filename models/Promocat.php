<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "promocat".
 *
 * @property int $id
 * @property int $promo_id
 * @property int $category_id
 */
class Promocat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promocat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['promo_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promo_id' => 'Promo ID',
            'category_id' => 'Category ID',
        ];
    }
}
