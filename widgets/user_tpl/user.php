<?php

use app\models\Transaction;
use app\models\User;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;


?>
<div class="row_boot" style="text-align: center;">
    <?php if (!empty($user)) :
        if (empty($user['photo'])) {
            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
        } else {
            $userphoto = Html::img("/images/user/user_{$user['id']}/200_200_{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
        }
    ?>


    <div class="col-md-5 useravatar">
        <a href="<?= yii\helpers\Url::to(['/profile']) ?>">
            <?= $userphoto ?>
        </a>
    </div>
        <div class="col-md-7 username">
            <?= Html::encode(($user->name) ? $user->name : $user->username) ?>
            <span class="" title="<?= $user['rate'] ?>(<?= (empty($user->rate_c)) ? "0" : $user->rate_c ?>)">
            <?php
            if (empty($user['role']) || $user['role'] != 'User') :
                echo StarRating::widget([
                    'name' => 'rating_'.$model->id.'',
                    'id' => 'input-'.$model->id.'',
                    'value' => $user['rate'],
                    'attribute' => 'rating',
                    'pluginOptions' => [
                        'size' => 'sm',
                        'stars' => 5,
                        'step' => 1,
                        'readonly' => true,
                        'disabled' => true,
                        'showCaption' => false,
                        'showClear'=>false
                    ]]); ?>

            <?php endif; ?>
            </span>
        </div>


        <div class="col-md-7 userbalance">
            <span>Balance (PAC):</span>
            <h3><?= Transaction::getUserBalance($user->id) ?>$</h3>
        </div>
        <div class="usermenu">
            <ul class="">
                <?php if (User::Can('createProject')):?><li class=""><a href="<?= Url::to('/catalog/create') ?>">New project</a></li><?php endif; ?>
                <?php if (User::Can('viewOwnProjects')):?><li class=""><a href="<?= Url::to('/profile/myprojects') ?>">My projects</a></li><?php endif; ?>
                <?php if (User::Can('viewPurchases')):?><li class=""><a href="<?= Url::to('/profile/purchases') ?>">My purchases</a></li><?php endif; ?>
                <?php if (User::Can('viewPayments')):?><li class=""><a href="<?= Url::to('/profile/payments') ?>">Payment history</a></li><?php endif; ?>
                <li class="">
                    <?php if (Yii::$app->user->isGuest) : ?>
                        <a href="<?= Url::to(['/login'])?>" data-method="post">Авторизация</a> | <a href="<?= Url::to(['/signup'])?>" data-method="post">Регистрация</a>
                    <?php else: ?>
                        <a href="<?= Url::to(['/logout'])?>" data-method="post">Выход</a>
                    <?php endif; ?>

                </li>
            </ul>
        </div>

    <?php endif; ?>
    <div class="col-md-12">
        <?php if (Yii::$app->user->isGuest) : ?>
        <a href="<?= Url::to(['/login'])?>" data-method="post">Авторизация</a> | <a href="<?= Url::to(['/signup'])?>" data-method="post">Регистрация</a>
        <?php /* else: ?>
        <a href="<?= Url::to(['/logout'])?>" data-method="post">Выход</a>
        <?php*/ endif; ?>

    </div>

</div>

