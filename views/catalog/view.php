<?php

use app\models\Catprod;
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
$catalias = Yii::$app->request->get('catalias'); //get category alias from url
$curentcat = Catprod::find()->where(['alias' => $catalias])->one();

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
if ($curentcat) {$this->params['breadcrumbs'][] = ['label' => ''.$curentcat->title.'', 'url' => ['catalog/'.$curentcat->alias.'']];}
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="products-view">

    <h1><?= Html::encode($model->title) ?></h1>

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
        <div class="col-md-6">
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
        <div class="col-md-6">
        <div id="r_infowrap<?=$model->id?>">
        <span id="numRait_<?=$model->id?>"><?= $model->getAllRatings($model->id) ?></span>/<span id="numVotes_<?=$model->id?>"><?= $model->getAllVotes($model->id) ?></span>
        </div>
        <?php
        echo StarRating::widget([
            'name' => 'rating_'.$model->id.'',
            'id' => 'input-'.$model->id.'',
            'value' => Ratings::getAllRatings($model->id),
            'attribute' => 'rating',
            'pluginOptions' => [
                'size' => 'xs',
                'stars' => 5,
                'step' => 1,
                'readonly' => Ratings::find()->where(['user_id' => yii::$app->user->id, 'project_id' => $model->id])->count() ? true : false,
                'disabled' => Yii::$app->user->isGuest ? true : false,
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
                            
                            if (typeof data.message !== "undefined") {
                                
                             }else{                                
                                $("#numRait_'.$model->id.'").text(data.r_rating);
                                $("#numVotes_'.$model->id.'").text(data.r_allrating);
                                inputRating.rating("refresh", {disabled: true});
                            }
                                inputRating.rating("reset");
                                $("#r_infowrap'.$model->id.' #rm_cont").detach();
                                $("#r_infowrap'.$model->id.'").append("<span id=\"rm_cont\" style=\"display:none\"></span>");
                                $("#r_infowrap'.$model->id.' #rm_cont").empty();
                                $("#r_infowrap'.$model->id.' #rm_cont").text(data.r_message).fadeIn(300).fadeOut(5000);   
                            
                        }                                              
                    });
                }',
        ],

        ]); ?>

        <?php
        if (empty($model->user->photo)) {
            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username), 'title' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username)]);
        } else {
            $userphoto = Html::img("/images/user/user_{$model->user->id}/50_50_{$model->user->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username), 'title' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username)]);
        }
        ?>
            <ul>

                <li><strong>Раздел: </strong><a href="<?= yii\helpers\Url::to(['/catalog/category', 'catalias' => $model->catprod->alias]) ?>"><?= Html::encode($model->catprod->title) ?></a></li>
                <li><strong>Автор: </strong>

                    <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $model->user->username]) ?>">
                        <span><?= $userphoto ?></span>
                        <span><?= Html::encode(($model->user->name) ? $model->user->name : $model->user->username) ?></span>
                    </a>
                </li>
                <li><strong>Портфолио: </strong><?= $model::find()->where(['user_id' => $model->user->id])->count() ?></li>
                <li><strong>Тематика: </strong><?= Html::encode($model->getThemslist()) ?></li>
                <li><strong>Метки: </strong><?= Html::encode($model->getTagslist()) ?></li>
                <li><strong>Загружено: </strong><?= Html::encode($model->created_at) ?></li>
                <li><strong>Просмотрено: </strong><?= Html::encode($model->hits) ?></li>
                <li><strong>Продано: </strong>0/<?= ($model->limit) ? $model->limit : "&infin;" ?></li>
                <li><strong>Инфо: </strong><br />
                    <?= Yii::$app->formatter->asNtext($model->project_info) ?></li>
                <?php /*<li><strong>Файл: </strong><?=  $galery[0]['filepath'].$galery[0]['filename']  ?></li>*/ ?>
            </ul>
        </div>
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


