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

    /* Products events */

    const EVENT_USER_NEW_PROJECT = 'New user project';

    public function init()
    {
        $this->on(Products::EVENT_USER_NEW_PROJECT, [$this, 'SendNewProjectAdminMail']);
    }

    // ==================== Send email about new User project
    public function SendNewProjectAdminMail($event)
    {
        $product = $event->sender;
        $image = json_decode($product->photos);

        $mail_admins = User::getUsersByIds(User::UsersByPermission('canReceiveNewProjectMail'));

        $messages = [];
        foreach ($mail_admins as $mailadmin) {
            $messages[] = Yii::$app->mailer->compose('userNewProjectEmail', ['imageProject' => $image[0]->foolpath, 'product' => $product])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name. ' (отправлено роботом).'])
                ->setTo($mailadmin->email)
                ->setSubject('Новый проект пользователя на сайте '.Yii::$app->name);

        }
        Yii::$app->mailer->sendMultiple($messages);
    }

    public static function tableName()
    {
        return 'products';
    }

    public function rules()
    {
        return [
            [['user_id', 'hits'], 'integer'],
            //[['price'], 'double'],
            [['price'], 'match', 'pattern' => '/^\d{0,3}[\,\.]{0,1}\d{0,2}$/i'],
            [['photos', 'category', 'price','tags', 'project_info', 'title', 'state', 'deleted'], 'required'],
            [['created_at'], 'safe'],
            [['file', 'title', 'project_path'], 'string', 'max' => 255],
            [['themes_index'], 'string', 'max' => 4055],
            [['photos', 'themes', 'themes_index'], 'safe'],
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
            'themes_index' => 'Themes index',
            'hits' => 'Hits',
            'state' => 'State',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    public static function isAuthor($prodid, $userid) //Проверка. Является ли пользователь автором продукта
    {
        $author = false;
        $prod_author = Products::findOne($prodid)->user_id;
        return $author = ($prod_author == $userid) ? true : false;
    }

    public static function getAutor($prodid)
    {
        $product = Products::findOne($prodid);
        return $product->user;
    }

    public static function checkOwner($prodid)
    {
        $product = Products::findOne($prodid);
        return $product->user->id;
    }

    public static function editableProject($project_id)
    {
        $limet = Products::findOne($project_id)->limit;
        $sales = Transaction::getProdSales($project_id);
        if ($limet > 0 && $sales > 0) {
            return false;
        }
        return true;
    }

    public static function checkLimit($prod_id)
    {
        $buys = Transaction::find()->where(['prod_id' => $prod_id, 'type' => 0])->count();
        $limit = Products::findOne($prod_id)->limit;
        if (empty($limit)) return true;
            if ($buys < $limit) {
                return true;
            } else {
                return false;
            }

    }


    public static function allowPurchased($prod_id)
    {
        if (Products::checkLimit($prod_id)) {
            $purchased = false;
            $owner = false;
            $purchased = Transaction::find()->where(['action_user' => Yii::$app->user->id, 'prod_id' => $prod_id, 'type' => 0])->one();
            $owner = (Products::checkOwner($prod_id) == Yii::$app->user->id) ? true : false;
            return ($purchased || $owner) ? false : true;
        } else {
            return true;
        }
    }


    public static function getCategory($prodid)
    {
        $product = Products::findOne($prodid);
        return $product->catprod;
    }

    /**
     * {@inheritdoc}
     */

    public function beforeValidate()
    {
        $this->price=5;
        return parent::beforeValidate();
    }

    public function getThemsprod()
    {
        return $this->hasMany(Themsprod::className(), ['id' => 'theme_id'])
                ->viaTable('project_thems', ['progect_id' => 'id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['prod_id' => 'id']);
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
        return $rating = $this->rating;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function saveProject($filename, $projectpath = '', $galery = '')
    {
        $this->file = $filename;
        $this->file_size = filesize($projectpath.'/'.$filename);;
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
        $total = count($this->tagsprod);
        $counter = 0;
        foreach ($this->tagsprod as $tag) {
            $counter++;
            if($counter == $total){
                print Html::a($tag->title, ['/catalog/tag', 'alias' => $tag->alias], ['class' => 'lisltnglinck']);
            }
            else{
                print Html::a($tag->title, ['/catalog/tag', 'alias' => $tag->alias], ['class' => 'lisltnglinck']).', ';
            }
        }

    }

    public function getThemslist()
    {
        $total = count($this->themsprod);
        $counter = 0;
        foreach($this->themsprod as $thema){
            $counter++;
            if($counter == $total){
                print Html::a($thema->title, ['/catalog/tema', 'alias' => $thema->alias], ['class' => 'lisltnglinck']);
            }
            else{
                print Html::a($thema->title, ['/catalog/tema', 'alias' => $thema->alias], ['class' => 'lisltnglinck']).', ';
            }
        }
    }

    public function getAllVotes($id)
    {
        $allvotes = 0;
        $allvotes = Ratings::find()->where(['project_id' => $id])->count(); //выбираем из базы рейтинга все отметки для текущего материала
        return $allvotes;
    }

    public static function getFileSize($prod_id)
    {
        $project = Products::findOne($prod_id);
        $path = $project->project_path.$project->file;
        $fileSize  = filesize($path);
        return $fileSize;
        //return Yii::$app->formatter->asShortSize($fileSize);
        //return Yii::$app->formatter->asShortSize(file_size);
    }

    //Сколько продаж проекта
    public static function getProjectSelling($project_id)
    {
        return Transaction::find()->where(['prod_id' => $project_id, 'type' => 1])->count();
    }

}
