<?php

use app\models\Cart;
use app\models\Products;
use app\models\Promotions;
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

                //получаем обект продукта
                //Подменяем цену в продуктк в соответствии со скидками
                $price = Promotions::getSalePrice($product->cartproduct);
                if ($price) $product->price = round($price['price'], 2);

/*
                if (!empty($price) && $product->price > 0) {
                    $this->product->price = $price['price'];//скидка не пуста - подменяем цену
                    $this->saleprice = $price;//скидка не пуста - подменяем цену
                }
*/
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
                    <h5>Product id#: <strong><?= Html::encode($product->product_id) ?></strong></h5>
                    <?php $author = Products::getAutor($product->product_id);?>
                    <h5><span class="glyphicon glyphicon-user"></span> Author: <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $author->username]) ?>">
                        <?=  Html::encode((!empty($author->name)) ? $author->name : $author->username) ?>
                    </a></h5>
                </td>
                <td>
                    <h3><?= Html::encode($product->price) ?>$</h3>
                    <?php if ($price['procent']) print '<h5><span class="bascetprocent">Sale: '.$price['procent'].'</span><br></h5>'; ?>
                    <?php if ($price['oldPrice']) print '<h5><span class="bascetoldprice">Price without discount: '.$price['oldPrice'].'$</span><h5>'; ?>
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
            <div class="col-md-3">На сумму:<br> <h3><span class="cartsummres"><?= Cart::getCartsummWS() ?></span>$</h3></div>
        </div>
    </div>
<hr class="col-md-12"/>
    <div class="row">
        <div class="col-md-4" style="text-align: center;">
            <?php
            $bstyle = 'success';
            if (Transaction::getUserBalance(Yii::$app->user->id) < Cart::getCartsummWS()) {$bstyle = 'danger';}
            ?>
            <button type="button" class="btn btn-<?= $bstyle ?> checkoutcart btn-lg">Personal account&nbsp;<span class="badge pull-right"><?= Transaction::getUserBalance(Yii::$app->user->id) ?>$</span></button>
        </div>
        <div class="col-md-4" style="text-align: center;">

        </div>
        <div class="col-md-4" style="text-align: center;">
            <div data-button="" class="paypal-buttonkit" role="button" aria-label="paypal" tabindex="0" style="padding:5px;border-radius: 5px;"><img class="paypal-button-logo paypal-button-logo-pp paypal-button-logo-gold" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAzMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWluWU1pbiBtZWV0Ij4KICAgIDxwYXRoIGZpbGw9IiMwMDljZGUiIGQ9Ik0gMjAuOTA1IDkuNSBDIDIxLjE4NSA3LjQgMjAuOTA1IDYgMTkuNzgyIDQuNyBDIDE4LjU2NCAzLjMgMTYuNDExIDIuNiAxMy42OTcgMi42IEwgNS43MzkgMi42IEMgNS4yNzEgMi42IDQuNzEgMy4xIDQuNjE1IDMuNiBMIDEuMzM5IDI1LjggQyAxLjMzOSAyNi4yIDEuNjIgMjYuNyAyLjA4OCAyNi43IEwgNi45NTYgMjYuNyBMIDYuNjc1IDI4LjkgQyA2LjU4MSAyOS4zIDYuODYyIDI5LjYgNy4yMzYgMjkuNiBMIDExLjM1NiAyOS42IEMgMTEuODI1IDI5LjYgMTIuMjkyIDI5LjMgMTIuMzg2IDI4LjggTCAxMi4zODYgMjguNSBMIDEzLjIyOCAyMy4zIEwgMTMuMjI4IDIzLjEgQyAxMy4zMjIgMjIuNiAxMy43OSAyMi4yIDE0LjI1OCAyMi4yIEwgMTQuODIxIDIyLjIgQyAxOC44NDUgMjIuMiAyMS45MzUgMjAuNSAyMi44NzEgMTUuNSBDIDIzLjMzOSAxMy40IDIzLjE1MyAxMS43IDIyLjAyOSAxMC41IEMgMjEuNzQ4IDEwLjEgMjEuMjc5IDkuOCAyMC45MDUgOS41IEwgMjAuOTA1IDkuNSI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iIzAxMjE2OSIgZD0iTSAyMC45MDUgOS41IEMgMjEuMTg1IDcuNCAyMC45MDUgNiAxOS43ODIgNC43IEMgMTguNTY0IDMuMyAxNi40MTEgMi42IDEzLjY5NyAyLjYgTCA1LjczOSAyLjYgQyA1LjI3MSAyLjYgNC43MSAzLjEgNC42MTUgMy42IEwgMS4zMzkgMjUuOCBDIDEuMzM5IDI2LjIgMS42MiAyNi43IDIuMDg4IDI2LjcgTCA2Ljk1NiAyNi43IEwgOC4yNjcgMTguNCBMIDguMTczIDE4LjcgQyA4LjI2NyAxOC4xIDguNzM1IDE3LjcgOS4yOTYgMTcuNyBMIDExLjYzNiAxNy43IEMgMTYuMjI0IDE3LjcgMTkuNzgyIDE1LjcgMjAuOTA1IDEwLjEgQyAyMC44MTIgOS44IDIwLjkwNSA5LjcgMjAuOTA1IDkuNSI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSA5LjQ4NSA5LjUgQyA5LjU3NyA5LjIgOS43NjUgOC45IDEwLjA0NiA4LjcgQyAxMC4yMzIgOC43IDEwLjMyNiA4LjYgMTAuNTEzIDguNiBMIDE2LjY5MiA4LjYgQyAxNy40NDIgOC42IDE4LjE4OSA4LjcgMTguNzUzIDguOCBDIDE4LjkzOSA4LjggMTkuMTI3IDguOCAxOS4zMTQgOC45IEMgMTkuNTAxIDkgMTkuNjg4IDkgMTkuNzgyIDkuMSBDIDE5Ljg3NSA5LjEgMTkuOTY4IDkuMSAyMC4wNjMgOS4xIEMgMjAuMzQzIDkuMiAyMC42MjQgOS40IDIwLjkwNSA5LjUgQyAyMS4xODUgNy40IDIwLjkwNSA2IDE5Ljc4MiA0LjYgQyAxOC42NTggMy4yIDE2LjUwNiAyLjYgMTMuNzkgMi42IEwgNS43MzkgMi42IEMgNS4yNzEgMi42IDQuNzEgMyA0LjYxNSAzLjYgTCAxLjMzOSAyNS44IEMgMS4zMzkgMjYuMiAxLjYyIDI2LjcgMi4wODggMjYuNyBMIDYuOTU2IDI2LjcgTCA4LjI2NyAxOC40IEwgOS40ODUgOS41IFoiPjwvcGF0aD4KPC9zdmc+Cg==" alt="pp" style="visibility: visible;"> <img class="paypal-button-logo paypal-button-logo-paypal paypal-button-logo-gold" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMTAwIDMyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiPgogICAgPHBhdGggZmlsbD0iIzAwMzA4NyIgZD0iTSAxMiA0LjkxNyBMIDQuMiA0LjkxNyBDIDMuNyA0LjkxNyAzLjIgNS4zMTcgMy4xIDUuODE3IEwgMCAyNS44MTcgQyAtMC4xIDI2LjIxNyAwLjIgMjYuNTE3IDAuNiAyNi41MTcgTCA0LjMgMjYuNTE3IEMgNC44IDI2LjUxNyA1LjMgMjYuMTE3IDUuNCAyNS42MTcgTCA2LjIgMjAuMjE3IEMgNi4zIDE5LjcxNyA2LjcgMTkuMzE3IDcuMyAxOS4zMTcgTCA5LjggMTkuMzE3IEMgMTQuOSAxOS4zMTcgMTcuOSAxNi44MTcgMTguNyAxMS45MTcgQyAxOSA5LjgxNyAxOC43IDguMTE3IDE3LjcgNi45MTcgQyAxNi42IDUuNjE3IDE0LjYgNC45MTcgMTIgNC45MTcgWiBNIDEyLjkgMTIuMjE3IEMgMTIuNSAxNS4wMTcgMTAuMyAxNS4wMTcgOC4zIDE1LjAxNyBMIDcuMSAxNS4wMTcgTCA3LjkgOS44MTcgQyA3LjkgOS41MTcgOC4yIDkuMzE3IDguNSA5LjMxNyBMIDkgOS4zMTcgQyAxMC40IDkuMzE3IDExLjcgOS4zMTcgMTIuNCAxMC4xMTcgQyAxMi45IDEwLjUxNyAxMy4xIDExLjIxNyAxMi45IDEyLjIxNyBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDM1LjIgMTIuMTE3IEwgMzEuNSAxMi4xMTcgQyAzMS4yIDEyLjExNyAzMC45IDEyLjMxNyAzMC45IDEyLjYxNyBMIDMwLjcgMTMuNjE3IEwgMzAuNCAxMy4yMTcgQyAyOS42IDEyLjAxNyAyNy44IDExLjYxNyAyNiAxMS42MTcgQyAyMS45IDExLjYxNyAxOC40IDE0LjcxNyAxNy43IDE5LjExNyBDIDE3LjMgMjEuMzE3IDE3LjggMjMuNDE3IDE5LjEgMjQuODE3IEMgMjAuMiAyNi4xMTcgMjEuOSAyNi43MTcgMjMuOCAyNi43MTcgQyAyNy4xIDI2LjcxNyAyOSAyNC42MTcgMjkgMjQuNjE3IEwgMjguOCAyNS42MTcgQyAyOC43IDI2LjAxNyAyOSAyNi40MTcgMjkuNCAyNi40MTcgTCAzMi44IDI2LjQxNyBDIDMzLjMgMjYuNDE3IDMzLjggMjYuMDE3IDMzLjkgMjUuNTE3IEwgMzUuOSAxMi43MTcgQyAzNiAxMi41MTcgMzUuNiAxMi4xMTcgMzUuMiAxMi4xMTcgWiBNIDMwLjEgMTkuMzE3IEMgMjkuNyAyMS40MTcgMjguMSAyMi45MTcgMjUuOSAyMi45MTcgQyAyNC44IDIyLjkxNyAyNCAyMi42MTcgMjMuNCAyMS45MTcgQyAyMi44IDIxLjIxNyAyMi42IDIwLjMxNyAyMi44IDE5LjMxNyBDIDIzLjEgMTcuMjE3IDI0LjkgMTUuNzE3IDI3IDE1LjcxNyBDIDI4LjEgMTUuNzE3IDI4LjkgMTYuMTE3IDI5LjUgMTYuNzE3IEMgMzAgMTcuNDE3IDMwLjIgMTguMzE3IDMwLjEgMTkuMzE3IFoiPjwvcGF0aD4KICAgIDxwYXRoIGZpbGw9IiMwMDMwODciIGQ9Ik0gNTUuMSAxMi4xMTcgTCA1MS40IDEyLjExNyBDIDUxIDEyLjExNyA1MC43IDEyLjMxNyA1MC41IDEyLjYxNyBMIDQ1LjMgMjAuMjE3IEwgNDMuMSAxMi45MTcgQyA0MyAxMi40MTcgNDIuNSAxMi4xMTcgNDIuMSAxMi4xMTcgTCAzOC40IDEyLjExNyBDIDM4IDEyLjExNyAzNy42IDEyLjUxNyAzNy44IDEzLjAxNyBMIDQxLjkgMjUuMTE3IEwgMzggMzAuNTE3IEMgMzcuNyAzMC45MTcgMzggMzEuNTE3IDM4LjUgMzEuNTE3IEwgNDIuMiAzMS41MTcgQyA0Mi42IDMxLjUxNyA0Mi45IDMxLjMxNyA0My4xIDMxLjAxNyBMIDU1LjYgMTMuMDE3IEMgNTUuOSAxMi43MTcgNTUuNiAxMi4xMTcgNTUuMSAxMi4xMTcgWiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny41IDQuOTE3IEwgNTkuNyA0LjkxNyBDIDU5LjIgNC45MTcgNTguNyA1LjMxNyA1OC42IDUuODE3IEwgNTUuNSAyNS43MTcgQyA1NS40IDI2LjExNyA1NS43IDI2LjQxNyA1Ni4xIDI2LjQxNyBMIDYwLjEgMjYuNDE3IEMgNjAuNSAyNi40MTcgNjAuOCAyNi4xMTcgNjAuOCAyNS44MTcgTCA2MS43IDIwLjExNyBDIDYxLjggMTkuNjE3IDYyLjIgMTkuMjE3IDYyLjggMTkuMjE3IEwgNjUuMyAxOS4yMTcgQyA3MC40IDE5LjIxNyA3My40IDE2LjcxNyA3NC4yIDExLjgxNyBDIDc0LjUgOS43MTcgNzQuMiA4LjAxNyA3My4yIDYuODE3IEMgNzIgNS42MTcgNzAuMSA0LjkxNyA2Ny41IDQuOTE3IFogTSA2OC40IDEyLjIxNyBDIDY4IDE1LjAxNyA2NS44IDE1LjAxNyA2My44IDE1LjAxNyBMIDYyLjYgMTUuMDE3IEwgNjMuNCA5LjgxNyBDIDYzLjQgOS41MTcgNjMuNyA5LjMxNyA2NCA5LjMxNyBMIDY0LjUgOS4zMTcgQyA2NS45IDkuMzE3IDY3LjIgOS4zMTcgNjcuOSAxMC4xMTcgQyA2OC40IDEwLjUxNyA2OC41IDExLjIxNyA2OC40IDEyLjIxNyBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjMDA5Y2RlIiBkPSJNIDkwLjcgMTIuMTE3IEwgODcgMTIuMTE3IEMgODYuNyAxMi4xMTcgODYuNCAxMi4zMTcgODYuNCAxMi42MTcgTCA4Ni4yIDEzLjYxNyBMIDg1LjkgMTMuMjE3IEMgODUuMSAxMi4wMTcgODMuMyAxMS42MTcgODEuNSAxMS42MTcgQyA3Ny40IDExLjYxNyA3My45IDE0LjcxNyA3My4yIDE5LjExNyBDIDcyLjggMjEuMzE3IDczLjMgMjMuNDE3IDc0LjYgMjQuODE3IEMgNzUuNyAyNi4xMTcgNzcuNCAyNi43MTcgNzkuMyAyNi43MTcgQyA4Mi42IDI2LjcxNyA4NC41IDI0LjYxNyA4NC41IDI0LjYxNyBMIDg0LjMgMjUuNjE3IEMgODQuMiAyNi4wMTcgODQuNSAyNi40MTcgODQuOSAyNi40MTcgTCA4OC4zIDI2LjQxNyBDIDg4LjggMjYuNDE3IDg5LjMgMjYuMDE3IDg5LjQgMjUuNTE3IEwgOTEuNCAxMi43MTcgQyA5MS40IDEyLjUxNyA5MS4xIDEyLjExNyA5MC43IDEyLjExNyBaIE0gODUuNSAxOS4zMTcgQyA4NS4xIDIxLjQxNyA4My41IDIyLjkxNyA4MS4zIDIyLjkxNyBDIDgwLjIgMjIuOTE3IDc5LjQgMjIuNjE3IDc4LjggMjEuOTE3IEMgNzguMiAyMS4yMTcgNzggMjAuMzE3IDc4LjIgMTkuMzE3IEMgNzguNSAxNy4yMTcgODAuMyAxNS43MTcgODIuNCAxNS43MTcgQyA4My41IDE1LjcxNyA4NC4zIDE2LjExNyA4NC45IDE2LjcxNyBDIDg1LjUgMTcuNDE3IDg1LjcgMTguMzE3IDg1LjUgMTkuMzE3IFoiPjwvcGF0aD4KICAgIDxwYXRoIGZpbGw9IiMwMDljZGUiIGQ9Ik0gOTUuMSA1LjQxNyBMIDkxLjkgMjUuNzE3IEMgOTEuOCAyNi4xMTcgOTIuMSAyNi40MTcgOTIuNSAyNi40MTcgTCA5NS43IDI2LjQxNyBDIDk2LjIgMjYuNDE3IDk2LjcgMjYuMDE3IDk2LjggMjUuNTE3IEwgMTAwIDUuNjE3IEMgMTAwLjEgNS4yMTcgOTkuOCA0LjkxNyA5OS40IDQuOTE3IEwgOTUuOCA0LjkxNyBDIDk1LjQgNC45MTcgOTUuMiA1LjExNyA5NS4xIDUuNDE3IFoiPjwvcGF0aD4KPC9zdmc+Cg==" alt="paypal" style="visibility: visible;"></div>
        <style>
            .paypal-buttonkit {
                background: #ffc439;
            }
            .paypal-buttonkit:hover {
                background: #f2ba36;
            }
        </style>
        </div>

    </div>
    <hr class="col-md-12"/>

</div>
    <?php Pjax::end(); ?>
</div>
