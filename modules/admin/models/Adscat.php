<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "adscat".
 *
 * @property int $id
 * @property string $parent_id
 * @property string $name
 * @property string $alias
 * @property string $metatitle
 * @property string $metakey
 * @property string $metadesc
 */
class Adscat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adscat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45],
            [['alias'], 'string', 'max' => 30],
            [['metatitle', 'metakey', 'metadesc'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'alias' => 'Alias',
            'metatitle' => 'Metatitle',
            'metakey' => 'Metakey',
            'metadesc' => 'Metadesc',
        ];
    }
}
