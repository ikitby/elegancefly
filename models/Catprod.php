<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catprod".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 */
class Catprod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catprod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['title', 'alias'], 'string', 'max' => 255],
            [['title'], 'unique'],
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
        ];
    }

    public function getProjects()
    {
        return $this->hasMany(Products::className(), ['category' => 'id']);
    }

}
