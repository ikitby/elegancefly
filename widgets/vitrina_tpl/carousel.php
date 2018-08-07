<?php

use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Json;
use yii\helpers\Url;

$owlId = uniqid("owlgallery_");

print '<h3>'.$items[0]->catprod->title.'</h3>';

OwlCarouselWidget::begin([
    'container' => 'div',
    'containerOptions' => [
        'class' => $owlId.' owlslide'
    ],
    'pluginOptions' => [
        'autoplay' => false,
        'autoplayTimeout' => 5000,
        'autoplayHoverPause'     => true,
        'loop'      => true,
        'lazyLoad'  => true,
        'nav'       => true,
        'dots'      => true,
        'checkVisible'      => true,
        'margin'    => 15,
        'items'     => $this->items_inline,
        'responsiveClass'     => true,
        'responsive' => [
            0 => ['items'=>2,'nav'=>true],
            500 => ['items'=>2,'nav'=>true],
            600 => ['items'=>3,'nav'=>true],
            800 => ['items'=>4,'nav'=>true],
            1200 => ['items'=>5,'nav'=>true],
        ],

    ]
]);

foreach ($items as $item) {
    $galery_teaser = json::decode($item->photos);
    print '<div  class="owl-items"><a href="'.Url::to(["/catalog/category", "catalias" => $item->catprod->alias, "id" => $item->id]).'" type="button" class=""><img class="owl-lazy img-responsive" data-src="/'.$galery_teaser[0]['filepath'].'400_400_'.$galery_teaser[0]['filename'].'" alt = "'.$item["title"].'" title = "'.$item["title"].'"></a></div>';
}

OwlCarouselWidget::end();

?>
