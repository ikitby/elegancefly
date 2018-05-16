<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "themsprod".
 *
 * @property int $id
 * @property string $title
 */
class Themsprod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'themsprod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'progect_id'])
            ->viaTable('project_thems', ['theme_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }
}
