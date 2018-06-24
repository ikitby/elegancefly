<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 24.06.2018
 * Time: 20:56
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;


class SetLimitProject extends ActiveRecord
{

    public function rules()
    {
        return [
            [['project_id', 'user_id', 'limit'], 'required'],
            [['project_id', 'user_id', 'limit', 'rating'], 'integer']
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
            'limit' => 'limit project'
        ];
    }

    public static function setLimit($id, $limit)
    {
        return $limit;
    }


}