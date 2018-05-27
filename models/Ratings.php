<?php

namespace app\models;

use Symfony\Component\VarDumper\Cloner\Data;
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
            [['project_id', 'user_id', 'rateuser_id', 'rating'], 'required'],
            [['project_id', 'user_id', 'rateuser_id', 'rating'], 'integer'],
            [['raiting_date'], 'safe'],
            ['rating', 'in', 'range' => [0, 1, 2, 3, 4, 5]]
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

    public function setRating($pid, $rate = 0)
    {
            $this->user_id = yii::$app->user->id; //id ставящего оценку
            $this->project_id = $pid; //Получаем id проекта
            $this->rateuser_id = $this->projects[0]->user_id; //Получаем id автора проекта
            $this->rating = $rate;

            if ($this::find()->where(['user_id' => $this->user_id, 'project_id' => $this->project_id])->one()) {
                return false;
            }

        if ($this->validate()) {
            $this->save();
            return 'Готово!';
        } else {
            return 'Фига се что случилось!'.$errors = $this->errors;
        }
    }


}

