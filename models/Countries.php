<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $alpha2
 * @property string $alpha3
 * @property int $numeric
 * @property string $fips
 * @property string $country
 * @property string $capital
 * @property string $continent
 * @property string $alias
 * @property int $discount
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
    }

    public function getCountryUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numeric', 'user_id', 'discount'], 'integer'],
            [['alpha2', 'fips', 'continent'], 'string', 'max' => 2],
            [['alpha3'], 'string', 'max' => 3],
            [['country', 'capital', 'alias'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alpha2' => 'Alpha2',
            'alpha3' => 'Alpha3',
            'numeric' => 'Numeric',
            'fips' => 'Fips',
            'country' => 'Country',
            'capital' => 'Capital',
            'continent' => 'Continent',
            'alias' => 'Alias',
            'discount' => 'Discount',
            'user_id' => 'user_id',
        ];
    }
}
