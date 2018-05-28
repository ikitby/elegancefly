<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\DetailView;
use ckarjun\owlcarousel\OwlCarouselWidget;
use kartik\rating\StarRating;
use app\models\Ratings;
//use app\models\Products;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);
        $galery = json::decode($model->photos); //декодим json с массивом галереи
        ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <h1><?= $model->title ?></h1>
        </div>
        <div class="col-md-4">
<?php

            OwlCarouselWidget::begin([
            'container' => 'div',
            'containerOptions' => [
            'id' => 'my-container-id',
            'class' => 'my-container-class'
            ],
            'pluginOptions' => [
            'autoPlay' => false,
            'items' => 1
            ]
            ]);

            foreach ($galery as $photo) {
            print Html::img('/'.$photo['filepath'].$photo['filename'], ['class' => 'img-responsive', 'style' => 'margin: 5px', 'alt' => $model->title, 'title' => $model->title]);
            }
            OwlCarouselWidget::end();

?>

        </div>
        <span id="numRait_<?=$model->id?>"><?= $model->getAllRatings($model->id) ?></span>/<span id="numVotes_<?=$model->id?>"><?= $model->getAllVotes($model->id) ?>

        <?php
        echo StarRating::widget([
            'name' => 'rating_'.$model->id.'',
            'id' => 'input-'.$model->id.'',
            'value' => $model->getAllRatings($model->id),
            'attribute' => 'rating',
            'pluginOptions' => [
                'size' => 'xs',
                'stars' => 5,
                'step' => 1,
                'readonly' => Ratings::find()->where(['user_id' => yii::$app->user->id, 'project_id' => $model->id])->count() ? true : false,
                //'disabled' => Yii::$app->user->isGuest ? true : false,
                'showCaption' => false,
                'showClear'=>false
            ],
            'pluginEvents' => [
            'rating:change' => 'function(event, value, caption) {
                   $.ajax({
                        type: "POST",
                        url: "/catalog/rate",
                        data: {"rating": value, "pid": '.$model->id.'},
                        cache: false,
                        success: function(data) {
                        
                            var data = jQuery.parseJSON(data);
                            var inputRating = $("#input-'.$model->id.'");
                            console.log (data);
                            
                            if (typeof data.message !== "undefined") {
                                console.log(data.r_message);
                                inputRating.rating("reset");
                                //inputRating.rating("reset");//очищает рейтинг до значения в бд

                                $("#myModal-geo .modal-body strong").text(data.message);//забиваем сообщение в модальное окно
                                $("#myModal-geo").modal();//вызываем виджет модального окна

                            }else{

                                $("#numRait_'.$model->id.'").text(data.r_rating);//обновляем цифры рейтинга в тегах на странице
                                $("#numVotes_'.$model->id.'").text(data.r_allrating);//обновляем цыфры кол-ва голосов в тегах на странице
                                inputRating.rating("refresh", {disabled: true, showClear: false, showCaption: true});//добавляет рейтинг и блокирует повторное нажатие
                            }

                        }
                    });
                    
                }',
        ],

        ]); ?>

        <?php
/*
        print StarRating::widget([
            'name' => 'rating_'.$model->id.'',
            'model' => 'dfs',
            'attribute' => 'rating',
            'id' => 'rating_'.$model->id.'',
            'value' => 2,
            'pluginOptions' => [
                'size' => 'xs',
                'stars' => 5,
                'step' => 1,
                'disabled'=>false,
                'showCaption' => false,
                'showClear'=>false
            ],
            'pluginEvents' => [
                'rating.change' => 'function(event, value, caption) {
                    alert ("ok!");
                }',
            ],
        ]);
*/
        ?>
            <ul>
                <li><strong>Автор: </strong><?= Html::encode(($model->user->name) ? $model->user->name : $model->user->username) ?></li>
                <li><strong>Портфолио: </strong><?= $model::find()->where(['user_id' => $model->user->id])->count() ?></li>
                <li><strong>Тематика: </strong><?= Html::encode($model->getThemslist()) ?></li>
                <li><strong>Метки: </strong><?= Html::encode($model->getTagslist()) ?></li>
                <li><strong>Загружено: </strong><?= Html::encode($model->created_at) ?></li>
                <li><strong>Просмотрено: </strong><?= Html::encode($model->hits) ?></li>
                <li><strong>Продано: </strong>0/<?= ($model->limit) ? $model->limit : "&infin;" ?></li>
                <li><strong>Инфо: </strong><?= Html::encode($model->project_info) ?></li>
                <?php /*<li><strong>Файл: </strong><?=  $galery[0]['filepath'].$galery[0]['filename']  ?></li>*/ ?>
            </ul>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'title',
            'file',
            'tags',
            [
                'format'  => 'html',
                'label' => 'Фото галерея',
                'value' => function($model) {;
                    $photos = json::decode($model->photos);
                    foreach ($photos as $photo) {
                        if ($photo['number'] != 0) {$photolist .= Html::img('/'.$photo['filepath'].'200_200_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '150', 'style' => 'margin: 5px', 'width' => '150', 'alt' => $model->title, 'title' => $model->title]);}
                        //$photolist = Html::img('/'.$photo['filepath'].'100_100_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '100', 'width' => '100', 'alt' => $model->title, 'title' => $model->title]);
                    }
                    return $photolist;
                },
            ],
            'price',
            'themes',
            'limit',
            'hits',
            'sales',
            'created_at',
        ],
    ]) ?>

</div>
