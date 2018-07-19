<?php

namespace app\widgets;
use yii\base\Widget;
use Yii;

class DepositWidget extends Widget{

    public $tpl;
    public $data;

    public function init(){
        parent::init();
        if ($this->tpl === null) {
            $this->tpl = 'deposit_paypal';
        }
        $this->tpl .='.php';
    }

	public function run() {
        //get cache
        //$deposit = Yii::$app->cache->get('deposit');
        //if ($deposit) return $deposit;

        $deposit = $this->getHtml($this->data);

        //set cache
        //Yii::$app->cache->set('deposit' , $deposit, 60*60 );
		return $deposit;
	}

	protected function getHtml($data) {
        $res = $this->getTemplate();
        return $res;
    }

    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/deposit_tpl/' . $this->tpl;
        return ob_get_clean();
    }
}