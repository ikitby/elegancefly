<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_tags".
 *
 * @property int $id
 * @property int $project_id
 * @property int $tag_id
 */
class ProjectTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'tag_id'], 'required'],
            [['project_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'tag_id' => 'Tag ID',
        ];
    }
}
