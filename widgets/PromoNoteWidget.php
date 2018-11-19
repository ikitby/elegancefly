<?php
namespace app\widgets;

use app\models\Promotions;
use app\models\Userevent;
use yii\base\Widget;
use app\models\Products;
use Yii;

class PromoNoteWidget extends Widget
{

    public $template;
    public $actionId;
    public $startDate;
    public $sended;


    public function init(){
        parent::init();

        $this->sended = ($this->getPromoEvent($this->actionId)) ? 1 : 0;
        if (!$this->actionId) $this->sended = 1;

        $this->template = 'default';

        $this->template .='.php';

    }


    public function run()
    {
        $PromoNote = $this->getHtml($this);
        return $PromoNote;
    }


    protected function getHtml($data) {
        $res = '';
        $res = $this->getTemplate();
        return $res;
    }


    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/sendButton_tpl/' . $this->template;
        return ob_get_clean();
    }

    protected function getPromoEvent($promoId){
        return Userevent::find()->where([
            'event_object' => $promoId,
            'event_type' => 'promonotify',
            //'event_progress' => 0
        ])->max('event_time');
    }

}