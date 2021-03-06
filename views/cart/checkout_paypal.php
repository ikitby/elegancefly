<?php

use app\models\Cart;
use app\models\Promotions;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use thamtech\uuid\helpers\UuidHelper;
use yii\helpers\Url;


$apiPaypal = Yii::$app->cm;
$apiContext = new apiContext(
    new OAuthTokenCredential($apiPaypal->client_id, $apiPaypal->client_secret)
);

$product = 'Check projects'; //Тенстовое название продукта
$price = Cart::getCartsummWS(); //Полдучаем стоимость товара в данном случае полную стоимость товаров в корзине для теста
$shipping = 0; //если доставка платная - указываем ее

$total = $price + $shipping; //калькулируем конечную стоимость с учетом доставки

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$item = new Item();
$item->setName($product)
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($price);

//перебираем товары для itemList
$ind = "0";
$items = array();
foreach ($cartprod as $prod) {
    //Пересчитаем новую цену товара в соответствии со скидками
    $sale = Promotions::getSalePrice($prod->cartproduct);
    $prod->price = (!empty($sale['price'])) ? $sale['price'] : $prod->price;
    //-------------------конец пересчата скидок---------------
    $item = new Item();
    $items[$ind] = $item->setName($prod->name)
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setPrice($prod->price);
    $ind++;
}
//перебираем товары для itemList

$itemList = new ItemList();
$itemList->setItems($items);

$details = new Details();
$details->setShipping($shipping)
        ->setSubtotal($price);

$amount = new Amount();
$amount->setCurrency('USD')
        ->setTotal($total)
        ->setDetails($details);

$transaction = new Transaction();
$transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription('TestPayment')
        ->setInvoiceNumber(uniqid());

// Блок для генерации ключа-идетификатора корзины (его добавим в базу ко всем записям корзины и к ReturnUrl - по нему дополнительно проверим принадлежность корзины оплате)

$buyer_id = Yii::$app->user->id;
$basket_uniq_id = UuidHelper::uuid();//Генерим ключ корзины для транзакции
Cart::updateAll(['basket_uniq_id' => $basket_uniq_id], ['buyer_id' => $buyer_id]); //Запихиваем ключ во все записи текущей козрины

// Конец блока генерации ключа

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl(Url::toRoute(['/cart/ext-checkout', 'success' => true, 'cid' => $basket_uniq_id], true))
        ->setCancelUrl(Url::toRoute(['/cart/ext-checkout', 'success' => false], true));

$payment = new Payment();
$payment->setIntent('sale')
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions([$transaction]);

try {
    $payment->create($apiContext);
} catch (Exception $e) {
    //dump($e);
    return '"INTERNAL_SERVICE_ERROR" - payment system error. Try again later';
}

$approvalUrl = $payment->getApprovalLink();

return Yii::$app->response->redirect($approvalUrl);
