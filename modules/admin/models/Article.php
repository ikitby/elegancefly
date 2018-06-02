<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "Article".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property string $categories
 * @property string $metatitle
 * @property string $metakey
 * @property string $metadesc
 * @property string $created
 * @property string $updated
 * @property string $blogimage
 * @property int $autor
 * @property int $state
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'updated'], 'safe'],
            [['autor'], 'integer'],
            [['title', 'metatitle', 'metakey', 'metadesc', 'blogimage'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 500],
            [['categories'], 'string', 'max' => 45],
            [['state'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'categories' => 'Categories',
            'metatitle' => 'Metatitle',
            'metakey' => 'Metakey',
            'metadesc' => 'Metadesc',
            'created' => 'Created',
            'updated' => 'Updated',
            'blogimage' => 'Blogimage',
            'autor' => 'Autor',
            'state' => 'State',
        ];
    }
}
