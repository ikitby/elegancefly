<?php

use app\models\Cart;
use app\models\Products;
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

$this->title = 'Download';
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
<hr />
    <div class="col-md-12 carttable">
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-3">Товаров в корзине:<br> <h3><span class="cartcountres"><?= Cart::getCartCount() ?></span></h3></div>
            <div class="col-md-3">На сумму:<br> <h3><span class="cartsummres"><?= Cart::getCartsumm() ?></span>$</h3></div>
            <div class="col-md-12">
                <button type="button" class="btn btn-danger emptycart"><span class="glyphicon glyphicon-trash"></span> Clear basket</button>
                <button type="button" class="btn btn-success pull-right checkoutcart">Checkout</button>
            </div>
        </div>
    </div>


</div>
    <?php Pjax::end(); ?>
</div>
