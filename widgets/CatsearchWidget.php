<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Products;
use Yii;

class CatsearchWidget extends Widget
{
    public $template;
    //public $products;
    public $painter;
    public $category;
    public $type;
    public $tags;

    public function init(){
        parent::init();
        if ($this->template === null) {
            $this->template = 'inline';
        }
        $this->template .='.php';
    }


    public function run()
    {
        $products = Products::find()
            ->where(['like', 'user_id', $this->painter])
            ->andWhere(['like', 'category', $this->category])
            ->all();

        dump($products);
        $products = $this->getHtml($products);
        return $products;
    }


    protected function getHtml($data) {
        $res = '';
            $res = $this->getTemplate();
        return $res;
    }


    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/search_tpl/' . $this->template;
        return ob_get_clean();
    }


}