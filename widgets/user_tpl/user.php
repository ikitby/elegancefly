<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row" style="text-align: center;">
    <?php if (!empty($user)) :
        (!$user->photo) ? $user->photo = 'nophoto.png' : $user->photo;
    ?>
    <div class="col-md-12 useravatar">
        <a href="<?= yii\helpers\Url::to(['/profile']) ?>">
            <?= Html::img("/images/user/{$user->photo}", ['class' => 'adaptive', 'alt' => Html::encode($user->name), 'title' => Html::encode($user->name)]) ?>
        </a>
    </div>
    <div class="col-md-12 username"><?= Html::encode($user->name) ?></div>
    <?php endif; ?>
    <div class="col-md-12">
        <?php if (Yii::$app->user->isGuest) : ?>
        <a href="<?= Url::to(['auth/login'])?>" data-method="post">Авторизация</a> | <a href="<?= Url::to(['auth/signup'])?>" data-method="post">Регистрация</a>
        <?php else: ?>
        <a href="<?= Url::to(['auth/logout'])?>" data-method="post">Выход</a>
        <?php endif; ?>
    </div>

</div>

