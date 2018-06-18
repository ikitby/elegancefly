<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\models\Products;
use app\models\Transaction;


class DownloadWidget extends Widget
{

    public $template;
    public $prod_id;
    private $aloowedDownload;
    private $prodDpwnload;

    public function init(){
        parent::init();
        if ($this->template === null) {
            $this->template = 'button';
        }
        $this->template .='.php';
    }

    public function run()
    {
        $this->aloowedDownload = !Transaction::checkPurchase(Yii::$app->user->id, $this->prod_id, 0);
        //checkPurchase принимает false если товар куплен - пользователь не может его купить, следовательно товар куплен клиент может скачать его
        dump($this);

        //$user = $this->getHtmlUser($this->user);

        $download = $this->getHtml($this);
        return $download;
    }

    protected function getHtml($data) {
        $res = '';
            $res = $this->getTemplate();
        return $res;
    }

    protected function getProuct($prod_id)
    {
        $user_id = Yii::$app->user->id;
        return Transaction::checkPurchase($user_id, $prod_id);
    }

    protected function getTemplate(){
        ob_start();
            include __DIR__ . '/download_tpl/' . $this->template;
        return ob_get_clean();
    }


}