<?php

use app\models\Transaction;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row" style="text-align: center;">
    <?php if (!empty($user)) :
        if (empty($user['photo'])) {
            $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
        } else {
            $userphoto = Html::img("/images/user/user_{$user['id']}/200_200_{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
        }
    ?>
    <div class="col-md-12 useravatar">
        <a href="<?= yii\helpers\Url::to(['/profile']) ?>">
            <?= $userphoto ?>
        </a>
    </div>
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
            ],
        ]); ?>
        <?= $user['rate'] ?>
        (<?= (empty($user->rate_c)) ? "0" : $user->rate_c ?>)
            <p>
                <?= Html::a('Добавить проект', ['/catalog/create'], ['class' => 'btn btn-success']) ?>
            </p>
        <?php endif; ?>
        <div class="col-md-12 username"><?= Html::encode($user->name) ?></div>

        <div class="col-md-12 userbalance">
            Баланс:
            <h3><?= Transaction::getUserBalance($user->id) ?>$</h3>
        </div>
        <div class="col-md-12 usermenu">
            <ul class="list-group">
                <li class="list-group-item"><a href="<?= Url::to('/profile/myprojects') ?>">My projects</a></li>
                <li class="list-group-item"><a href="<?= Url::to('/profile/purchases') ?>">My purchases</a></li>
                <li class="list-group-item"><a href="<?= Url::to('/profile/payments') ?>">Payment history</a></li>
                <li class="list-group-item">
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

