<?php
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
    <div class="col-md-12 username"><?= Html::encode($user->name) ?></div>
    <?php endif; ?>
    <div class="col-md-12">
        <?php if (Yii::$app->user->isGuest) : ?>
        <a href="<?= Url::to(['/login'])?>" data-method="post">Авторизация</a> | <a href="<?= Url::to(['/signup'])?>" data-method="post">Регистрация</a>
        <?php else: ?>
        <a href="<?= Url::to(['/logout'])?>" data-method="post">Выход</a>
        <?php endif; ?>

    </div>

</div>

