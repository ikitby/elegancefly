<?php
namespace app\widgets;

use app\models\Promotions;
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
    private $saleprice;

    //states ot basket:
    //0 - disable
    //1 - allow
    //2 - download
    //3 - owner

    public function init(){
        parent::init();
        $this->state = 1;

        //Подменяем цену в продуктк в соответствии со скидками
        $price = Promotions::getSalePrice($this->product);

        if (!empty($price) && $this->product->price > 0) {
            $this->product->price = $price['price'];//скидка не пуста - подменяем цену
            $this->saleprice = $price;//скидка не пуста - подменяем цену
        }

        if ($this->template === null) {
            $this->template = 'plane';
        }

        $this->count = count($this->product->transactions)/2; // Получаем количество транзакций и делим пополам для продукта
        $this->limit = $this->product->limit;

        if ($this->limit == $this->count && !empty($this->limit)) {
            $this->state = 0;
        }

        if (!empty($this->product->transactions)) {
            foreach ($this->product->transactions as $transaction) {
                if ($transaction->action_user == Yii::$app->user->id && $transaction->type == 0) {
                    $this->state = 2;
                    break;
                }
            }
        }

        if ($this->product->user_id == Yii::$app->user->id) {
            $this->state = 3;
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