<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_thems".
 *
 * @property int $id
 * @property int $progect_id
 * @property int $theme_id
 */
class ProjectThems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_thems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['progect_id', 'theme_id'], 'required'],
            [['progect_id', 'theme_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'progect_id' => 'Progect ID',
            'theme_id' => 'Theme ID',
        ];
    }
}
