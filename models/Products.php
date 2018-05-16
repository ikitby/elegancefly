<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $user_ad
 * @property string $Архив для загрузки
 * @property string $tags
 * @property string $photos
 * @property int $price
 * @property string $themes
 * @property int $limit
 * @property int $hits
 * @property int $sales
 * @property string $created_at
 */
class Products extends \yii\db\ActiveRecord
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
            [['user_ad', 'price', 'limit', 'hits', 'sales'], 'integer'],
            [['photos', 'price', 'themes', 'created_at'], 'required'],
            [['created_at'], 'safe'],
            [['Архив для загрузки', 'tags', 'photos', 'themes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_ad' => 'User Ad',
            'Архив для загрузки' => 'Архив для загрузки',
            'tags' => 'Tags',
            'photos' => 'Photos',
            'price' => 'Price',
            'themes' => 'Themes',
            'limit' => 'Limit',
            'hits' => 'Hits',
            'sales' => 'Sales',
            'created_at' => 'Created At',
        ];
    }
}
