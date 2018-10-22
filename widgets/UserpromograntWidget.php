<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Promotions;

use Yii;

class UserpromograntWidget extends Widget
{

    public $template;
    public $product;
    private $promo;
    public $script;

    public function init(){
        parent::init();

        //получаем скидку и считаем цену в продуктк в соответствии с ней для информирования автора
        $this->promo = Promotions::getSalePriceUser($this->product);

        if ($this->template === null) {
            $this->template = 'default';
        }

        $this->template .='.php';
    }

    public function run()
    {
        if (!$this->promo) return false;
        $promoUP = $this->getHtml($this);
        return $promoUP;
    }

    protected function getHtml($data) {
        $res = '';
        $res = $this->getTemplate();
        return $res;
    }

    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/userpromogrant_tpl/' . $this->template;
        return ob_get_clean();
    }

}