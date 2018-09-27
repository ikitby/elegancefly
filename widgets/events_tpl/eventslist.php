<?php
use yii\helpers\Html;

if (!$event->eventUser->photo) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode($event->eventUser->username), 'title' => Html::encode($event->eventUser->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$event->eventUser->id}/50_50_{$event->eventUser->photo}", ['class' => 'img-responsive', 'width' => '50px', 'alt' => Html::encode($event->eventUser->username), 'title' => Html::encode($event->eventUser->username)]);
}

?>
<div class="media">
    <a class="pull-left" href="<?= yii\helpers\Url::to(['/admin/users/view', 'id' => $user['id']]) ?>">
        <?= $userphoto ?>
    </a>
    <div class="media-body">
        <h5 class="media-heading"><?= $event->event_desc ?></h5>
        <span class="label label-default"><?= $event->event_time ?></span>
    </div>
</div>
