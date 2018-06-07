<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Products;
use Yii;

class BasketWidget extends Widget
{

    public $template;
    public $prod_id;
    public $price;
    public $discont;
    public $limit;


    public function init(){
        parent::init();
        if ($this->template === null) {
            $this->template = 'plane';
        }
        $this->template .='.php';
    }


    public function run()
    {
        $basket = $this->getHtml($this);
        return $basket;
    }


    protected function getHtml($data) {
        $res = '';
            $res = $this->getTemplate();
        return $res;
    }


    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/basket_tpl/' . $this->template;
        return ob_get_clean();
    }


}