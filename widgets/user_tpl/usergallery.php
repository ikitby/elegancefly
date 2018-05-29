<?php
use yii\helpers\Html;
if (empty($user['photo'])) $user['photo'] = 'nophoto.png';
?>
<li>
    <a href="<?= yii\helpers\Url::to(['/users', 'user' => $user['username']]) ?>">
        <?= Html::img("/images/user/{$user['photo']}", ['class' => 'adaptive', 'alt' => $user['name'], 'title' => $user['name']]) ?>
    </a>
</li>