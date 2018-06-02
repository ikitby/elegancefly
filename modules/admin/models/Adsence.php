<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "adsence".
 *
 * @property int $id
 * @property int $userid
 * @property string $autorname
 * @property int $category
 * @property string $title
 * @property int $state
 * @property string $text
 * @property string $adsimg
 * @property string $adsphone
 * @property string $adsphone1
 * @property string $email
 * @property string $created
 * @property string $updated
 * @property string $metatitle
 * @property string $metakey
 * @property string $metadesc
 * @property int $products
 */
class Adsence extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adsence';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userid', 'category', 'state', 'products'], 'integer'],
            [['text'], 'required'],
            [['created', 'updated'], 'safe'],
            [['autorname'], 'string', 'max' => 250],
            [['title', 'adsimg', 'adsphone', 'adsphone1', 'email', 'metatitle', 'metakey', 'metadesc'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'autorname' => 'Autorname',
            'category' => 'Category',
            'title' => 'Title',
            'state' => 'State',
            'text' => 'Text',
            'adsimg' => 'Adsimg',
            'adsphone' => 'Adsphone',
            'adsphone1' => 'Adsphone1',
            'email' => 'Email',
            'created_at' => 'Created_at',
            'updated_at' => 'Updated_at',
            'metatitle' => 'Metatitle',
            'metakey' => 'Metakey',
            'metadesc' => 'Metadesc',
            'products' => 'Products',
        ];
    }
}
