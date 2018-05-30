<?php
use yii\helpers\Html;
if (empty($user['photo'])) $user['photo'] = 'nophoto.png';
?>
<li>
    <a href="<?= yii\helpers\Url::to(['/painters', 'user' => $user['username']]) ?>">
        <?= Html::img("/images/user/{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]) ?>
    </a>
</li>