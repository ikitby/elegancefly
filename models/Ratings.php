<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ratings".
 *
 * @property int $id
 * @property int $progect_id
 * @property int $user_id
 * @property int $rateuser_id
 * @property int $rating
 * @property string $raiting_date
 */
class Ratings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ratings';
    }

    public static function getAllRating ($itemid)
    {
        $rating = 0;
        $ratingall = self::find()->where(['project_id' => $itemid])->select('rating')->all(); //выбираем из базы рейтинга все отметки для текущего материала
        $i = 0;

        foreach ($ratingall as $rate)
        {
            $rating += $rate->rating;
            $i++;
        }

        if ($rating > 0) {
            $rating = round($rating / $i, 1);
        }

        return $rating;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'user_id', 'rateuser_id', 'rating', 'raiting_date'], 'required'],
            [['project_id', 'user_id', 'rateuser_id', 'rating'], 'integer'],
            [['raiting_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Progect ID',
            'user_id' => 'User ID',
            'rateuser_id' => 'Rateuser ID',
            'rating' => 'Rating',
            'raiting_date' => 'Raiting Date',
        ];
    }

    public function getProjects()
    {
        return $this->hasMany(Products::className(), ['id' => 'project_id']);
    }

}

