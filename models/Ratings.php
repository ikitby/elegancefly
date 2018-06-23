<?php

namespace app\models;


use Yii;
use yii\helpers\Json;

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
    public $allrating;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ratings';
    }

    public static function getAllRatings($id)
    {
        $rating = 0;
        $rating = self::find()->where(['project_id' => $id])->sum('rating'); //выбираем из базы рейтинга все отметки для текущего материала
        $count = self::find()->where(['project_id' => $id])->count();

                if ($count > 0) {
                    $rating = round($rating / $count, 1);
                }

        return $rating;
    }

    private static function getAllUserRatingCount($user_id)
    {
        return self::find()->where(['rateuser_id' => $user_id])->count();
    }

    private static function getAllUserRating($userid)
    {
        $rating = 0;
        $rating = self::find()->where(['rateuser_id' => $userid])->sum('rating'); //выбираем из базы рейтинга все отметки для текущего материала
        $count = self::find()->where(['rateuser_id' => $userid])->count();

                if ($count > 0) {
                    $rating = round($rating / $count, 1);
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

    public function getAllRating($id)
    {
        $rating = 0;
        $rating = self::find()->where(['project_id' => $id])->sum('rating'); //выбираем из базы рейтинга все отметки для текущего материала
        $count = self::find()->where(['project_id' => $id])->count();

        if ($count > 0) {
            $rating = round($rating / $count, 1);
        }

        return $rating;
    }

    public function getAllVotes ($itemid)
    {
        return $AllVotes = self::find()->where(['project_id' => $itemid])->select('rating')->count(); //выбираем из базы рейтинга все отметки для текущего материала

    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'rateuser_id']);
    }

    public function setRating($pid, $rate = 0)
    {

            $this->user_id = yii::$app->user->id; //id ставящего оценку
            $this->project_id = $pid; //Получаем id проекта
            $this->rateuser_id = $this->projects[0]->user_id; //Получаем id автора проекта
            $this->rating = $rate;

           // if ($this::find()->where(['user_id' => $this->user_id, 'project_id' => $this->project_id])->one()) {
           //     return false;
           // }

            if ($this->checkReate($pid)) {
                    if ($this->validate()) {
                        $this->save();
                        $resresult['r_rating'] = $this->getAllRating($pid);
                        $resresult['r_allrating'] = $this->getAllVotes($pid);
                        $resresult['r_message'] = 'Ваш голос принят';
                        //Запишем рейтинг в запись проекта для сортировок и ускорения выводе рейтина в дальнейшем
                        $prate = Products::findOne($pid);
                        $prate->rating = $resresult['r_rating'];
                        $prate->tatng_votes = $resresult['r_allrating'];
                        $prate->save();
                        //Запишем рейтинг в запись проекта для сортировок и ускорения выводе рейтина в дальнейшем
                        //обновим общий рейтинг пользователя за которого голосовали
                        $urate = User::findOne($this->projects[0]->user_id);
                        $newrate = Ratings::getAllUserRating($this->projects[0]->user_id);
                        $newrate_c = Ratings::getAllUserRatingCount($this->projects[0]->user_id);
                        $urate->rate = $newrate;
                        $urate->rate_c = $newrate_c;
                        $urate->save(false);
                        //обновим общий рейтинг пользователя за которого голосовали
                        return Json::encode($resresult);
                    } else {
                        return Json::encode($resresult['r_error'] ='Что то меня заклинило!'.$errors = $this->errors);
                    }
            } else {
                $resresult['r_message'] = 'Вы уже оценили эту работу...';
                return Json::encode($resresult);
            }
    }

    private function checkReate($pid)
    {
        return (Ratings::find()->where(['user_id' => yii::$app->user->id, 'project_id' => $pid])->all()) ? false : true; //Проверка голосовал ли текущий пользователь за материал
    }

}

