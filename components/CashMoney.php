<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 06.07.2018
 * Time: 18:00
 */

namespace app\components;

use yii\base\Component;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class CashMoney extends Component {
    public $client_id;
    public $client_secret;
    public $isProduction;

    private $apiContext; // paypal API context

    // override Yii object init()
    function init() {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential($this->client_id, $this->client_secret)
        );
    }

    public function getTotalAmount()
    {
        dump($this);
    }

    public function getContext() {
        return $this->apiContext;
    }
}