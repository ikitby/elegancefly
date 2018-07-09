<?php

use app\models\Cart;
use app\models\Products;
use app\models\Transaction;
use app\widgets\BasketWidget;
use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cart';
$this->params['breadcrumbs'][] = $this->title;

//$photos = json::decode($cartprod->photos);

?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
<div id="ajcartwrapp">
    <table class="table table-hover carttable">
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>

                </th>
                <th>
                    Info
                </th>
                <th>
                    Price
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
            $i = 1;
            foreach ($cartprod as $product) :

            $catalias = $product->cartproduct->catprod->alias;
        ?>

            <tr class="cartrowpr" id="catprodrow_<?= $product->id ?>">
                <td>
                    <?= $i ?>
                </td>
                <td>
                    <a href="<?= Url::to(["/catalog/category", "catalias" => $catalias, "id" => $product->product_id]) ?>">
                        <?= Html::img('/'.$product->img, ['class' => 'img-responsive', 'alt' => Html::encode($product->name), 'title' => Html::encode($product->name)]); ?>
                    </a>
                </td>
                <td>
                    <h4>
                        <a href="<?= Url::to(["/catalog/category", "catalias" => $catalias, "id" => $product->product_id]) ?>"><?= Html::encode($product->name) ?></a>
                    </h4>
                    Product id: <strong><?= Html::encode($product->product_id) ?></strong><br />
                    <?php $author = Products::getAutor($product->product_id);?>
                    <span class="glyphicon glyphicon-user"></span> <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $author->username]) ?>">
                        <?=  Html::encode($author->name) ?>
                    </a><br />
                </td>
                <td>
                    <h4><?= Html::encode($product->price) ?>$</h4>
                </td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', "#", [
                        'class' => 'btn btn-danger btn-xs pull-right delfromcart',
                        'data' => [
                            'id' => "$product->id",
                            'confirm' => 'Are you sure you want to delete '.$product->name.'?',
                        ],
                    ]) ?>
                </td>
            </tr>

        <?php
            $i++;
            endforeach;
        ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger emptycart pull-right"><span class="glyphicon glyphicon-trash"></span> Clear basket</button>
        </div>
    </div>
<hr class="col-md-12"/>
    <div class="col-md-12 carttable">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-3">Товаров в корзине:<br> <h3><span class="cartcountres"><?= Cart::getCartCount() ?></span></h3></div>
            <div class="col-md-3">На сумму:<br> <h3><span class="cartsummres"><?= Cart::getCartsumm() ?></span>$</h3></div>
        </div>
    </div>
<hr class="col-md-12"/>
    <div class="row">
        <div class="col-md-6" style="text-align: center;">
            <button type="button" class="btn btn-success checkoutcart btn-lg">Personal account (<?= Transaction::getUserBalance(Yii::$app->user->id) ?>$)</button>
        </div>
        <div class="col-md-6" style="text-align: center;">

            <script src="https://www.paypalobjects.com/api/checkout.js"></script>

            <div id="paypal-button-container"></div>

            <script>

                // Render the PayPal button

                paypal.Button.render({

                    // Set your environment

                    env: 'sandbox', // sandbox | production

                    // Specify the style of the button

                    style: {
                        layout: 'vertical',  // horizontal | vertical
                        size:   'medium',    // medium | large | responsive
                        shape:  'rect',      // pill | rect
                        color:  'gold'       // gold | blue | silver | black
                    },

                    // Specify allowed and disallowed funding sources
                    //
                    // Options:
                    // - paypal.FUNDING.CARD
                    // - paypal.FUNDING.CREDIT
                    // - paypal.FUNDING.ELV

                    funding: {
                        allowed: [ paypal.FUNDING.CARD, paypal.FUNDING.CREDIT ],
                        disallowed: [ ]
                    },

                    // PayPal Client IDs - replace with your own
                    // Create a PayPal app: https://developer.paypal.com/developer/applications/create

                    client: {
                        sandbox:    'AT9WezRKiN9rgintHa9sXVwywTCPPampyCFSheab7AecgZC3BO---EpUW5b-RK581H6xTcW92_LV9Ru4',
                        production: '<insert production client id>'
                    },

                    payment: function(data, actions) {
                        return actions.payment.create({
                            payment: {
                                transactions: [
                                    {
                                        amount: { total: '<?= Cart::getCartsumm() ?>', currency: 'USD' }
                                    }
                                ]
                            }
                        });
                    },

                    onAuthorize: function(data, actions) {
                        return actions.payment.execute().then(function() {
                            window.alert('Payment Complete!');
                        });
                    }

                }, '#paypal-button-container');

            </script>



        </div>
    </div>
    <hr class="col-md-12"/>


</div>
    <?php Pjax::end(); ?>
</div>
