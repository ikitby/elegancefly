<?php

use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Json;
use yii\helpers\Url;

foreach ($items as $item) {
    $galery_teaser = json::decode($item->photos);
    print '<div  class="userprojectgallery"><a href="'.Url::to(["/catalog/category", "catalias" => $item->catprod->alias, "id" => $item->id]).'" type="button" class=""><img class="img-responsive" src="/'.$galery_teaser[0]['filepath'].'200_200_'.$galery_teaser[0]['filename'].'" alt = "'.$item["title"].'" title = "'.$item["title"].'"></a></div>';
}


?>
