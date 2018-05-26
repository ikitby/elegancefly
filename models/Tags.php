<?php

namespace app\models;

use dastanaron\translit\Translit;
use Yii;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "project_tags".
 *
 * @property int $id
 * @property int $progect_id
 * @property int $tag_id
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }


    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'project_id'])
            ->viaTable('project_tags', ['tag_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    public function createTag($tag_id)
    {
        $newtag = Tags::findOne(['title' => $tag_id]);
        if($newtag)
        {
            return $newtag;
        } else {
            $this->title = $tag_id;
            $this->alias = $this->translite($tag_id);
            $this->save();
            return $this;
        }
    }

    private function translite($text)
    {
        $translit = new Translit();
        return $text = strtolower($translit->translit($text, true, 'ru-en'));
    }




    /**
     * {@inheritdoc}
     */
    /*
    public function rules()
    {
        return [
            [['progect_id', 'tag_id'], 'required'],
            [['progect_id', 'tag_id'], 'integer'],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
}
