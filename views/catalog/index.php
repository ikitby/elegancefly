<?php

use app\widgets\BasketWidget;
use app\widgets\CatsearchWidget;
use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
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

    <?php //Pjax::begin();

    if (!empty($searchModel)) {
        print $this->render('_search', ['model' => $searchModel]);
    }

    ?>

    <div class="row">
    <?php
    if (!empty($products)) :
        foreach ($products as $product):
        $galery_teaser = json::decode($product->photos);
    ?>

    <?php $owlId = uniqid('owl_'); ?>
<div class="col-md-3 <?= $owlId ?>">

 <?php
    OwlCarouselWidget::begin([
    'container' => 'div',
    'containerOptions' => [
            'class' => $owlId.' owl-loaded'
    ],
    'pluginOptions' => [
     'autoplay' => false,
     'autoplayTimeout' => 1000,
    'autoplayHoverPause'     => true,
    'loop'     => true,
    'lazyLoad' => true,
    'items'    => 1
    ]
    ]);
    foreach ($galery_teaser as $photo) {
        print '<div  class="owl-items"><a href="'.Url::to(["/catalog/category", "catalias" => $product->catprod->alias, "id" => $product->id]).'" type="button" class=""><img class="owl-lazy img-responsive" data-src="/'.$photo['filepath'].'200_200_'.$photo['filename'].'" alt = "'.$photo["title"].'" title = "'.$photo["title"].'"></a></div>';
    }

    OwlCarouselWidget::end();
?>

 <?= BasketWidget::widget([
     'template' =>'plane_w_download',
     'product' => $product
 ])
 ?>

</div>

    <?php
    endforeach;
    else:
        throw new BadRequestHttpException('Empty category');
        ?>
    <?php endif;
    ?>
      <div class="row">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
      </div>
    </div>

    <?php //Pjax::end(); ?>
</div>
