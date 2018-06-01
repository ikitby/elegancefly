<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "statuses".
 *
 * @property int $id
 * @property string $status
 * @property string $alias
 * @property int $discount
 */
class Statuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discount'], 'integer'],
            [['status', 'alias'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'alias' => 'Alias',
            'discount' => 'Discount',
        ];
    }

    public function getUser()
    {
        return $this->hasMany(User::className(), ['usertype' => 'id']);
    }

}
