<?php

namespace app\models;

use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
    //public $photos;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return 'products';
    }

    public static function getAutor($prodid)
    {
        $product = Products::findOne($prodid);
        return $product->user;
    }

    public static function getCategory($prodid)
    {
        $product = Products::findOne($prodid);
        return $product->catprod;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'limit', 'hits', 'sales'], 'integer'],
            [['photos', 'category', 'price','tags', 'project_info', 'themes','title', 'state', 'deleted'], 'required'],
            [['created_at'], 'safe'],
            [['file', 'title', 'project_path'], 'string', 'max' => 255],
            [['photos'], 'safe'],
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
            'category' => 'Category',
            'file' => 'File',
            'tags' => 'Tags',
            'photos' => 'Photos',
            'project_path' => 'Project path',
            'price' => 'Price',
            'project_info' => 'Project info',
            'themes' => 'Themes',
            'limit' => 'Limit',
            'hits' => 'Hits',
            'sales' => 'Sales',
            'state' => 'State',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    public function getThemsprod()
    {
        return $this->hasMany(Themsprod::className(), ['id' => 'theme_id'])
                ->viaTable('project_thems', ['progect_id' => 'id']);
    }

    public function getTagsprod()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tag_id'])
            ->viaTable('project_tags', ['project_id' => 'id']);
    }

    public function getRateUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('ratings', ['project_id' => 'id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Ratings::className(), ['project_id' => 'id']);
    }

    public function getCatprod()
    {
        return $this->hasOne(Catprod::className(), ['id' => 'category']);
    }

    public function getAllRatings ($itemid)
    {
        $rating = 0;
        $ratingall = Ratings::find()->where(['project_id' => $itemid])->select('rating')->all(); //выбираем из базы рейтинга все отметки для текущего материала
        $i = 0;
        foreach ($ratingall as $rate)
        {
            $rating += $rate->rating;
            $i++;
        }

        if ($rating > 0) {
            $rating = round($rating / $i, 1);
        }
        return $rating;
    }



    public function afterFind() {
        //$this->rating = Ratings::getAllRating($this->id);
        return $rating = $this->rating;
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

    public function getItemtags ()
    {
        $selTags = $this->getTagsprod()->select('id')->asArray()->all();
        $selTags = ArrayHelper::getColumn($selTags, 'id');
        return $selTags;
    }

    public function saveThems($themes)
    {
        if(is_array($themes))
        {
            $this->clearCurrentTems();

            foreach ($themes as $theme_id)
            {
                $themsprod = Themsprod::findOne($theme_id); // link tems
                $this->link('themsprod', $themsprod);
            }
        }
    }

    public function clearCurrentTems()
    {
        ProjectThems::deleteAll(['progect_id' => $this->id]);
    }

    public function clearCurrentTags()
    {
        ProjectTags::deleteAll(['project_id' => $this->id]);
    }

    public function saveTags($tags)
    {
        if(is_array($tags))
        {
            $this->clearCurrentTags();

            foreach ($tags as $tag_id)
            {
                $prodtags = Tags::findOne($tag_id); // link tags
                if ($prodtags)
                {
                    $this->link('tagsprod', $prodtags);
                } else {
                    $newtag = new Tags(); //Создаем экземпляр модели тега
                    $tagobj = $newtag->createTag($tag_id);
                    $this->link('tagsprod', $tagobj);//Делаем запрос на создание нового тега
                }

            }

        }
    }

    public function getTagslist()
    {
        foreach ($this->tagsprod as $tag) {
                print Html::a($tag->title, ['/catalog/tag', 'alias' => $tag->alias], ['class' => 'btn btn-default btn-xs']);
        }
    }

    public function getThemslist()
    {
        foreach ($this->themsprod as $thema) {
                print Html::a($thema->title, ['/catalog/tema', 'alias' => $thema->alias], ['class' => 'btn btn-default btn-xs']);
        }
    }

    public function getAllVotes($id)
    {
        $allvotes = 0;
        $allvotes = Ratings::find()->where(['project_id' => $id])->count(); //выбираем из базы рейтинга все отметки для текущего материала
        return $allvotes;
    }


}
