<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $user_ad
 * @property string $Архив для загрузки
 * @property string $tags
 * @property string $photos
 * @property int $price
 * @property string $themes
 * @property int $limit
 * @property int $hits
 * @property int $sales
 * @property string $created_at
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'limit', 'hits', 'sales'], 'integer'],
            [['photos', 'price', 'themes'], 'required'],
            [['created_at'], 'safe'],
            [['file', 'tags', 'photos', 'themes'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User id',
            'file' => 'File',
            'tags' => 'Tags',
            'photos' => 'Photos',
            'price' => 'Price',
            'themes' => 'Themes',
            'limit' => 'Limit',
            'hits' => 'Hits',
            'sales' => 'Sales',
            'created_at' => 'Created At',
        ];
    }

    public function getThemsprod()
    {
        return $this->hasMany(Themsprod::className(), ['id' => 'theme_id'])
                ->viaTable('project_thems', ['progect_id' => 'id']);
    }

    public function saveProject($filename)
    {
        $this->photo = $filename;
        $this->save(false);
    }

    public function getTems()
    {
        $selThems = $this->getThemsprod()->asArray()->all();
        $selThems = ArrayHelper::getColumn($selThems, 'title');

        return $selThems;
    }

}
