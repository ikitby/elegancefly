<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Self_;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "promotions".
 *
 * @property int $id
 * @property int $created_at
 * @property int $action_start
 * @property int $action_end
 * @property string $action_title
 * @property int $action_percent
 * @property string $action_descr
 * @property string $action_catergories
 * @property string $action_userroles
 * @property string $action_mailtext
 * @property int $action_autor
 * @property int $action_state
 */
class Promotions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promotions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_percent', 'action_autor', 'action_state'], 'integer'],
            [['created_at', 'action_start', 'action_end'], 'string', 'max' => 20],
            [['action_descr', 'action_mailtext'], 'string'],
            [['action_title'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'action_start' => 'Начало акции',
            'action_end' => 'Окончание акции',
            'action_title' => 'Заголовок акции',
            'action_percent' => 'Процент скидки',
            'action_descr' => 'Описание акции',
            'action_catergories' => 'Категории работ',
            'action_userroles' => 'Роли пользователей',
            'action_mailtext' => 'Текст письма рассылки',
            'action_autor' => 'Promotion Autor',
            'action_state' => 'Promotion State',
        ];
    }

    // Связи проектов с акцией через таблицу promotion_products
    private function _getPromProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'prod_id'])
            ->viaTable('promotion_products', ['prom_id' => 'id']);
    }

/*    public function getCatprod()
    {
        return $this->hasMany(Catprod::className(), ['id' => 'category_id'])
            ->viaTable('promocat', ['promo_id' => 'id']);
    }
*/
    public function getPromocat()
    {
        return $this->hasMany(Catprod::className(), ['id' => 'category_id'])
            ->viaTable('promocat', ['promo_id' => 'id']);
    }



    public function getPromPod($promId = 1) {
        $Products = $this->_getPromProducts()->select(['id','title','user_id','category','photos','price'])->all();
        return $Products;
    }

    public function getPromPodId($promId = 1) {
        $Products = $this->_getPromProducts()->select('id')->asArray()->all();
        $selProducts = ArrayHelper::getColumn($Products, 'id');
        return $selProducts;
    }

    //возвращает объект скидки если такойй соответствует датам и разделу
    private static function _getActiveProm($product) {

        $catId = $product->category;

        $promo = Promotions::find()
            //->With('promocat')
            ->joinWith('promocat')
            ->Where(['in', 'promocat.category_id', $catId])
            ->andWhere([/*'action_catergories' => $catId,*/ 'action_state' => 1])
            ->andWhere(['<', 'action_start', date('Y-m-d h:m')])
            ->andWhere(['>', 'action_end', date('Y-m-d h:m')])
            ->one();
        return $promo;
    }

    //Возвращает объект скидки
    public static function getSale($product) {
        //Получаем объект скидки
        $promo = Promotions::_getActiveProm($product->category);
        //если обекст пуст - вылетаем по false
        if (empty($promo)) return false;
        //В итоге возвращаем скидку цельным объектом
        return $promo;
    }

    //Возвращает новый массив цены с процентом сидки, экономией, старой ценой, новой ценой
    public static function getSalePrice($product) {

        if ($product->price <= 0 || empty($product->price)) return false;

        $salePrice = array();
        //Получаем объект скидки
        $promo = Promotions::_getActiveProm($product);

        //если обекст пуст - вылетаем по false
        if (empty($promo)) return false;

        $price = $product->price;

        //получаем цену с учетом скидки
        $salePrice = [
            'procent' => '-'.$promo->action_percent.'%',
            'price' => $price-($price*$promo->action_percent/100),
            'oldPrice' => $price,
            'economy' => $price-($price*$promo->action_percent/100)
            ];

        return $salePrice;
    }

    public function getPromocats()
    {
        $selCats = $this->getPromocat()->select('id')->asArray()->all();
        $selCats = ArrayHelper::getColumn($selCats, 'id');
        return $selCats;
    }

    public function savePromocat($categories)
    {
        if(is_array($categories))
        {
            $this->clearCurrentCats();

            foreach ($categories as $theme_id)
            {
                $promocat = Catprod::findOne($theme_id); // link tems
                $this->link('promocat', $promocat);
            }
        }
    }

    public function clearCurrentCats()
    {
        Promocat::deleteAll(['promo_id' => $this->id]);
    }


}
