<?php

namespace app\widgets;
use app\models\Products;
use yii\base\Widget;
use Yii;

class UserProjectsWidget extends Widget
{
    public $tpl;
    public $user_id;
    public $items_count;
    public $current_item;
    public $items;

    public function init(){
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'gallery';
        }
        if ($this->user_id === null) {
            $this->user_id = 0;
        }
        if ($this->items_count === null) {
            $this->items_count = '8';
        }
        if ($this->current_item === null) {
            $this->current_item = '0';
        }
        $this->tpl .='.php';
    }

    public function run() {
        //get cache
        //$items = Yii::$app->cache->get('vitrina');
        //if ($items) return $items;

        $this->items = Products::find()
            ->where(['user_id' => $this->user_id, 'state' => 1, 'deleted' => 0])
            ->andWhere(['not', ['id' => $this->current_item]])
            ->with('catprod')
            ->orderBy('RAND()')
            ->all();
        $items = $this->getHtmliItems($this->items);

        //set cache
       //Yii::$app->cache->set('blogmenu' , $menu, 60*60*24 );
        return $items;
    }

    protected function getHtmliItems($items) {
        return $this->getTemplate($items);
    }

    protected function getTemplate($items){
        ob_start();
        include __DIR__ . '/userprojects_tpl/' . $this->tpl;
        return ob_get_clean();
    }

}