<?php

use app\models\Catprod;
use app\widgets\BasketWidget;
use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$catalias = Yii::$app->request->get('catalias'); //get category alias from url
$curentcat = Catprod::find()->where(['alias' => $catalias])->one();

$this->title = $curentcat->title;

$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$photos = json::decode($model->photos);

?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить проект', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row">
    <?php
    if (!empty($products)) :
        foreach ($products as $product):
            $galery_teaser = json::decode($product->photos);
            $allowpurchased = true;
            $limit = $product->limit;
            $count = count($product->transactions);
            $allowpurchased = ($limit > $count) ? true : false;
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
    'autoPlay' => false,
    'loop'     => true,
    'lazyLoad' => true,
    'items'    => 1
    ]
    ]);
    foreach ($galery_teaser as $photo) {
        print '<div  class="owl-items"><a href="'.Url::to(["/catalog/category", "catalias" => $curentcat->alias, "id" => $product->id]).'" type="button" class=""><img class="owl-lazy img-responsive" data-src="/'.$photo['filepath'].'200_200_'.$photo['filename'].'" alt = "'.$photo["title"].'" title = "'.$photo["title"].'"></a></div>';
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

</div>
