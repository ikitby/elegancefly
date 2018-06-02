<?php
use yii\helpers\Html;
if (empty($user['photo'])) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
} else {
    $userphoto = Html::img("/images/user/user_{$user['id']}/100_100_{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
}
?>
<li>
    <a href="<?= yii\helpers\Url::to(['/painters', 'user' => $user['username']]) ?>">
        <?= $userphoto ?>
    </a>
</li>