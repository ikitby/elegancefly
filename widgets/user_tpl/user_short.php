<?php

use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?= $this->render('_newactions') ?>

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
        <div class="col-md-7">
            <span class="username">
                <?= Html::encode(($user->name) ? $user->name : $user->username) ?>
            </span>
            <span class="" title="<?= $user['rate'] ?>(<?= (empty($user->rate_c)) ? "0" : $user->rate_c ?>)">
            <?php
            if (empty($user['role']) || $user['role'] != 'User') :
                echo StarRating::widget([
                    'name' => 'rating_'.$user->id.'',
                    'id' => 'input-'.$user->id.'',
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
            <a href="<?= yii\helpers\Url::to(['/profile']) ?>" class="cablinck">
                Личный кабинет
            </a>
        </div>

    <?php endif; ?>
    <div class="col-md-12">
        <?php if (Yii::$app->user->isGuest) : ?>
        <span class="authuser">
        <a href="<?= Url::to(['/login'])?>" data-method="post">Авторизация</a> | <a href="<?= Url::to(['/signup'])?>" data-method="post">Регистрация</a>
        </span>
            <?php /* else: ?>
        <a href="<?= Url::to(['/logout'])?>" data-method="post">Выход</a>
        <?php*/ endif; ?>

    </div>

</div>

