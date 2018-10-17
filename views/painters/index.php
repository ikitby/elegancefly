<?php

use app\models\Transaction;
use app\models\User;
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

        <div id="painterspage" class="row">
            <?php
            if (!empty($users)) :
                foreach ($users as $user):
                    ?>
                    <div class="paintergeed">
                      <div class="row">
                        <div class="col-md-4">
                            <?php
                            $username = ($user['name']) ? $user['name'] : $user['username'];
                            if (empty($user['photo'])) {
                            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user->name) ? $user->name : $user->username), 'title' => Html::encode(($user->name) ? $user->name : $user->username)]);
                            } else {
                            $userphoto = Html::img("/images/user/user_{$user['id']}/{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user->name) ? $user->name : $user->username), 'title' => Html::encode(($user->name) ? $user->name : $user->username)]);
                            }
                            ?>
                            <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $username]) ?>">
                            <?= $userphoto ?>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <a href="<?= yii\helpers\Url::to(['/painters/user', 'alias' => $username]) ?>">
                                <h3><?= $username ?></h3>
                            </a>
                            <span style="font-size: 10px">
                            <?php
                            echo StarRating::widget([
                                'name' => 'rating_'.$user['id'].'',
                                'id' => 'input_'.$user['id'].'',
                                'value' => $user['rate'],
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
                            <ul class="authorteaser">
                                <?php if ($user->userCountry->country) : ?><li>Country: <?= $user->userCountry->country ?></li><?php endif; ?>
                                <li>Работ: <a href="<?= Url::to(['/catalog/painter', 'painter' => $username]) ?>"><?= Html::encode(User::getUserProjectsCount($user['id'])) ?></a></li>
                                <li>Продаж: <?= Transaction::getUserSales($user->id) ?></li>
                            </ul>
                        </div>

                      </div>
                    </div>

                    <?php
                endforeach;
            else: ?>
                ooops...
            <?php endif;
            ?>
            <div class="row">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
        </div>

    </div>
