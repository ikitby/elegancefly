<?php

use app\models\Transaction;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;

if (!$event->eventUser['photo']) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode($event->eventUser['username']), 'width' => '50px', 'title' => Html::encode($event->eventUser['username'])]);
} else {
    $userphoto = Html::img("/images/user/user_{$event->eventUser['id']}/50_50_{$event->eventUser['photo']}", ['class' => 'img-responsive', 'width' => '50px', 'alt' => Html::encode($event->eventUser['username']), 'title' => Html::encode($event->eventUser['username'])]);
}

?>
<div class="media" id="eventid_<?= $event->eventUser['id'] ?>">
    <a class="pull-left" href="<?= yii\helpers\Url::to(['/admin/users/view', 'id' => $event->eventUser['id']]) ?>">
        <?= $userphoto ?>
    </a>
    <div class="media-body">
        <h5 class="media-heading"><?= $event->event_desc ?></h5>
        <span class="label label-default"><?= $event->event_time ?></span>
        <div class="eventactions">

            <?php
            $userBalance = Transaction::getUserBalance($event->event_user);
            ?>
            <div class="acceptblock">
             <div>
            <?= TouchSpin::widget([
                    'name' => 'cacheamount',
                    'pluginOptions' => [
                        'initval' => floor($userBalance),
                        //'min' => Yii::$app->params['minLimitCasheMoney'],
                        'step' => 0.1,
                        'decimals' => 2,
                        'max' => $userBalance,
                        'prefix' => '$',
                        'placeholder' => '...'],
                ]) ?>
                <div class="btn-group cachesend" style=" display: flex;">
                    <a class="btn btn-success approve_cache_request" href="" data-event="<?= $event['id'] ?>" data-id="<?= $event->eventUser['id'] ?>">Готово</a>
                    <a class="btn btn-danger close_cache_request" title="Отмена" href="" ><span class="glyphicon glyphicon-remove"></a>
                </div>

             </div>
                <h5 style="margin: 0;">сумма округлена до максимального целого</h5>
            </div>
            <div class="btn-group actmanage">
            <a class="btn btn-success btn-xs approve_usercache" href="" title="Выбрать сумму и подтвердить вывод денег со счета пользователя">Вывести</a>
            <a class="btn btn-danger btn-xs refuse_cache_request" title="Отказать в обналичке с уведомлением пользователя по email" href="" data-event="<?= $event['id'] ?>" data-id="<?= $event->eventUser['id'] ?>"><span class="glyphicon glyphicon-remove"></a>
            <a class="btn btn-warning btn-xs delete_cache_request" title="Удалить запись о запросе обналички без уведомления пользователя" href="" data-event="<?= $event['id'] ?>" data-id="<?= $event->eventUser['id'] ?>"><span class="glyphicon glyphicon-trash"></a>
            </div>
        </div>
    </div>
</div>
