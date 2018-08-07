<?php

namespace app\widgets;
use app\models\Products;
use yii\base\Widget;
use Yii;

class VitrinaWidget extends Widget
{
    public $tpl;
    public $category_id;
    public $items_count;
    public $items_inline;
    public $items;

    public function init(){
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'carousel';
        }
        if ($this->category_id === null) {
            $this->category_id = 1;
        }
        if ($this->items_count === null) {
            $this->items_count = '16';
        }
        if ($this->items_inline === null) {
            $this->items_inline = '4';
        }


        $this->tpl .='.php';
    }

    public function run() {
        //get cache
        //$items = Yii::$app->cache->get('vitrina');
        //if ($items) return $items;

        $this->items = Products::find()
            ->where(['category' => $this->category_id])
            ->with('catprod')
            ->orderBy(['created_at' => SORT_DESC])
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
        include __DIR__ . '/vitrina_tpl/' . $this->tpl;
        return ob_get_clean();
    }

}