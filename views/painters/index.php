<?php

use app\models\Transaction;
use ckarjun\owlcarousel\OwlCarouselWidget;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Painters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php// Pjax::begin(); ?>
        <div class="row">
            <?php
            if (!empty($users)) :
                foreach ($users as $user):
                    ?>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-4">
                            <?php
                            if (empty($user->photo)) {
                            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user->name) ? $user->name : $user->username), 'title' => Html::encode(($user->name) ? $user->name : $user->username)]);
                            } else {
                            $userphoto = Html::img("/images/user/user_{$user->id}/{$user->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($user->name) ? $user->name : $user->username), 'title' => Html::encode(($user->name) ? $user->name : $user->username)]);
                            }
                            ?>
                            <?= $userphoto ?>
                        </div>
                        <div class="col-md-8">
                            <?= ($user->name) ? $user->name : $user->username ?>
                            <span style="font-size: 10px">
                            <?php
                            echo StarRating::widget([
                                'name' => 'rating_'.$user->id.'',
                                'id' => 'input-'.$user->id.'',
                                'value' => $user->rate,
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
                            Работ: <?= $user->getUserProjectsCount($user->id) ?><br/>
                            Продаж: <?= Transaction::getUserSales($user->id) ?>
                        </div>

                      </div>
                    </div>

                    <?php
                endforeach;
            else: ?>
                Нет тут ни чего!
            <?php endif;
            ?>
            <div class="row">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
        </div>

        <?php // Pjax::end(); ?>
    </div>
