<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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

    public static function getArtIdsFromTemaId($thema)
    {
        $thems = ProjectThems::find()->select('progect_id')->asArray(['tag_id'])->where(['theme_id' => $thema])->all();
        return (ArrayHelper::getColumn($thems, 'progect_id'));
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
