<?php

namespace app\models;

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

    public function getPromPod($promId = 1) {
        $Products = $this->_getPromProducts()->select(['id','title','user_id','category','photos','price'])->all();
        return $Products;
    }

    public function getPromPodId($promId = 1) {
        $Products = $this->_getPromProducts()->select('id')->asArray()->all();
        $selProducts = ArrayHelper::getColumn($Products, 'id');
        return $selProducts;
    }



}
