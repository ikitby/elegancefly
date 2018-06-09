<?php

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

$this->title = 'Cart';
$this->params['breadcrumbs'][] = $this->title;

//$photos = json::decode($cartprod->photos);

?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
<div id="ajcartwrapp">
    <table class="table table-hover">
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
        <?php
            $i = 1;
            foreach ($cartprod as $product) :
            $catalias = $product->cartproduct->catprod->alias;
        ?>
        <tbody>
            <tr>
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
                    Product id: <?= Html::encode($product->product_id) ?><br />
                    <?php $author = Products::getAutor($product->product_id);?>
                    <?=  Html::encode($author->name) ?><br />
                </td>
                <td>
                    <?= Html::encode($product->price) ?>$
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
        </tbody>
        <?php
            $i++;
            endforeach;
        ?>
    </table>
</div>
    <?php Pjax::end(); ?>
</div>
