<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string $seo_title
 * @property string $seo_keyworlds
 * @property string $seo_desc
 * @property string $text
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['text'], 'string'],
            [['title', 'seo_title'], 'string', 'max' => 250],
            [['alias'], 'string', 'max' => 20],
            [['seo_keyworlds', 'seo_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'alias' => 'Alias',
            'seo_title' => 'Seo Title',
            'seo_keyworlds' => 'Seo Keyworlds',
            'seo_desc' => 'Seo Desc',
            'text' => 'Text',
        ];
    }
}
