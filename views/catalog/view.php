<?php

use app\models\Catprod;
use app\models\Transaction;
use app\models\User;
use app\widgets\BasketWidget;
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

$allowpurchased = true;
$limit = $model->limit;
$count = count($model->transactions);
$allowpurchased = ($limit > $count) ? true : false;

?>
<div class="products-view">

    <h1><?= Html::encode($model->title) ?></h1>

    <?php $galery = json::decode($model->photos); //декодим json с массивом галереи ?>

    <div class="row">
        <div class="col-sm-6 col-md-6">
<?php

            OwlCarouselWidget::begin([
            'container' => 'div',
            'containerOptions' => [
            'id' => 'project_gallery',
            'class' => 'project_gallery_cls'
            ],
            'pluginOptions' => [
            'autoPlay' => true,
            'nav' => true,
            'dots' => true,
            'autoplay' => true,
            'lazyLoad' => true,
            'loop' => true,
            'items' => 1
            ]
            ]);

            foreach ($galery as $photo) {
            print Html::img('/'.$photo['filepath'].$photo['filename'], ['class' => 'img-responsive', 'style' => 'margin: 5px', 'alt' => $model->title, 'title' => $model->title]);
            }
            OwlCarouselWidget::end();
?>
<?php
echo StarRating::widget([
    'name' => 'rating_'.$model->id.'',
    'id' => 'input-'.$model->id.'',
    'value' => $model->rating,
    'attribute' => 'rating',
    'pluginOptions' => [
        'size' => 'xs',
        'stars' => 5,
        'step' => 1,
        'filledStar' => '<i class="glyphicon glyphicon-heart"></i>',
        'emptyStar' => '<i class="glyphicon glyphicon-heart-empty"></i>',
        'defaultCaption' => '{rating} hearts',
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

            <div class="raitcount" id="r_infowrap<?=$model->id?>">
                <span id="numRait_<?=$model->id?>"><?= $model->rating ?></span>/<span id="numVotes_<?=$model->id?>"><?= ($model->tatng_votes) ? $model->tatng_votes : 0 ?></span>
            </div>

        </div>
        <div class="col-sm-6 col-md-6">



        <?php
        if (empty($model->user->photo)) {
            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username), 'title' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username)]);
        } else {
            $userphoto = Html::img("/images/user/user_{$model->user->id}/100_100_{$model->user->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username), 'title' => Html::encode(($model->user->name) ? $model->user->name : $model->user->username)]);
        }
        ?>
            <ul id="paramsproject">
                <li class="authorblock">
                    <div class="row">
                        <div class="col-sm-3">
                            <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $model->user->username]) ?>">
                                <span><?= $userphoto ?></span>
                            </a>
                        </div>
                        <div class="col-sm-9">
                            <strong>Painter:
                            <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $model->user->username]) ?>">
                                <span><?= Html::encode(($model->user->name) ? $model->user->name : $model->user->username) ?></span>
                            </a></strong>
                            <span style="font-size: 0.5em">
                            <?php
                            echo StarRating::widget([
                                'name' => 'rating_'.$model->user->id.'',
                                'id' => 'input-'.$model->user->id.'',
                                'value' => $model->user->rate,
                                'attribute' => 'rating',
                                'pluginOptions' => [
                                    'size' => 'xs',
                                    'stars' => 5,
                                    'step' => 1,
                                    'readonly' => true,
                                    'disabled' => true,
                                    'showCaption' => false,
                                    'showClear'=>false
                                ],
                            ]); ?>
                            </span>

                            <strong>Projects: </strong><a href="<?= Url::to(['/catalog/painter', 'painter' => $model->user->username]) ?>"><?= Html::encode(User::getUserProjectsCount($model->user->id)) ?></a>
                        </div>
                    </div>
                </li>
                <li>
                    <div id="priceblock">
                        <?= BasketWidget::widget(['template' =>'plane_w_download_inline', 'product' => $model]) ?>
                    </div>
                </li>
                <li><strong>ID: </strong><?= Html::encode($model->id) ?></li>
                <li class="paramslist"><strong>Uploaded: </strong><?= Yii::$app->formatter->asDate($model->created_at) ?></li>
                <li class="paramslist"><strong>Views: </strong><?= Html::encode($model->hits) ?></li>

                <li class="paramslist info"><strong>Project info: </strong><br />
                    <?= Yii::$app->formatter->asNtext($model->project_info) ?></li>
                <li class="paramslist info"><strong>Сopyrights: </strong><br />
                    <div id="copyrightsblock">
                    © <?= Html::encode(($model->user->name) ? $model->user->name : $model->user->username) ?><br />
                    <?= Url::home(true) ?>
                    </div>
                </li>
            </ul>
        </div>
        </div>
    <div class="row">
<div class="col-md-6">
    <ul class="list-unstyled">
        <?php /*<li class="paramslist">
            <strong>Раздел: </strong><a href="<?= yii\helpers\Url::to(['/catalog/category', 'catalias' => $model->catprod->alias]) ?>"><?= Html::encode($model->catprod->title) ?></a>
        </li> */
        ?>
        <li class="paramslist"><strong>Thems: </strong><?= Html::encode($model->getThemslist()) ?></li>
        <li class="paramslist"><strong>Tags: </strong><?= Html::encode($model->getTagslist()) ?></li>
        <li class="paramslist"><strong>More <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $model->user->username]) ?>">
                    <?= Html::encode(($model->user->name) ? $model->user->name : $model->user->username) ?></a> projects:</strong>
            <div id="userprojectsgalery">
            <?= \app\widgets\UserProjectsWidget::widget(['user_id' => $model->user->id, 'current_item' => $model->id]) ?>
            </div>
        </li>
    </ul>
</div>
<div class="col-md-6" id="facecomments">
    <div class="fb-comments" data-href="<?= Url::current([], true); ?>" data-width="100%" data-numposts="5"></div>
</div>
</div>
</div>



