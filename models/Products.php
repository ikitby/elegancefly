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
            [['photos', 'price', 'themes','title'], 'required'],
            [['created_at'], 'safe'],
            [['file', 'tags', 'photos', 'title', 'project_path'], 'string', 'max' => 255],
            [['themes'], 'each', 'rule' => ['integer']],
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
            'title' => 'Title',
            'file' => 'File',
            'tags' => 'Tags',
            'photos' => 'Photos',
            'project_path' => 'Project path',
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function saveProject($filename, $projectpath = '', $galery = '')
    {
        $this->file = $filename;
        $this->project_path = $projectpath;
        $this->photos = $galery;
        $this->save(false);
    }

    public function getTems()
    {
        $selThems = $this->getThemsprod()->select('id')->asArray()->all();
        $selThems = ArrayHelper::getColumn($selThems, 'id');
        return $selThems;
    }

    public function saveThems($themes)
    {
        if(is_array($themes))
        {
            $this->clearCurrentTags();

            foreach ($themes as $theme_id)
            {
                $themsprod = Themsprod::findOne($theme_id); // linck tags
                $this->link('themsprod', $themsprod);
            }
        }
    }

    public function clearCurrentTags()
    {
        ProjectThems::deleteAll(['progect_id' => $this->id]);
    }




}
