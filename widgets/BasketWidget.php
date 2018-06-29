<?php
namespace app\widgets;

use yii\base\Widget;
use app\models\Products;
use Yii;

class BasketWidget extends Widget
{

    public $template;
    public $product;
    private $count;
    private $limit;
    private $state;

    //states ot basket:
    //0 - disable
    //1 - allow
    //2 - download
    //3 - owner

    public function init(){
        parent::init();
        $this->state = 1;

        if ($this->template === null) {
            $this->template = 'plane';
        }

        $this->count = count($this->product->transactions)/2; // Получаем количество транзакций и делим пополам для продукта
        $this->limit = $this->product->limit;
        foreach ($this->product->transactions as $transaction)
        {
            $this->state = ($transaction->action_user == Yii::$app->user->id && $transaction->type == 0) ? 2 : 1;
            break;
        }
        $this->limit = $this->product->limit;

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