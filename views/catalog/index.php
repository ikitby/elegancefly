<?php

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

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;

$photos = json::decode($model->photos);

?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <p>
        <?= Html::a('Добавить проект', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
    <?php
    if (!empty($products)) :
        foreach ($products as $product):
        $galery_teaser = json::decode($product->photos);
    ?>

    <?php $owlId = uniqid('owl_'); ?>
<div class="col-md-2 <?= $owlId ?>">

 <?php
    OwlCarouselWidget::begin([
    'container' => 'div',
    'containerOptions' => [
            'class' => $owlId.' owl-loaded'
    ],
    'pluginOptions' => [
    'autoPlay' => false,
    'loop'     => true,
    'lazyLoad' => true,
    'items'    => 1
    ]
    ]);
    foreach ($galery_teaser as $photo) {
        print '<div  class="owl-items"><a href="'.Url::to(["view", "id" => $product->id]).'" type="button" class=""><img class="owl-lazy img-responsive" data-src="/'.$photo['filepath'].'200_200_'.$photo['filename'].'" alt = "'.$photo["title"].'" title = "'.$photo["title"].'"></a></div>';
    }
    OwlCarouselWidget::end();
?>
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
