<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;

$photos = json::decode($model->photos);

?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
    <?php
    if (!empty($products)) :
        foreach ($products as $product):
        $galery_teaser = json::decode($product->photos);
    ?>
<div class="col-md-2">
    <?= '<a href="'.Url::to(["view", "id" => $product->id]).'" type="button" class="">'.Html::img( '/'.$galery_teaser[0]['filepath'].$galery_teaser[0]['filename'], ['class' => 'img-responsive', 'alt' => $product->title, 'title' => $product->title]).'</a>' ?>
</div>

    <?php
    endforeach;
        print LinkPager::widget(['pagination' => $pagination]);
    else: ?>
        Нет тут ни чего!
    <?php endif;
    ?>
    </div>

    <?php Pjax::end(); ?>
</div>
