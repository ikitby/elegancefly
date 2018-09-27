<?php

use app\widgets\Alert;
use yii\helpers\Html;
use app\models\User;
//AdminAsset::register($this);

?>
<div class="admin-default-index">
<h1>Пользователи</h1>
<div class="row">

    <div class="container" style="background-color: #212528;color:  #ccc;">
        <div class="col-sm-3" style="text-align: center; color: #eed77d;">
            Пользователей:
            <h1><?= User::getUsersCount(['Painter','Creator','User']) ?></h1>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Покупателей:
            <h1><?= User::getUsersCount('User') ?></h1>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Художников:
            <h1><?= User::getUsersCount('Painter') ?></h1>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Творцов:
            <h1><?= User::getUsersCount('Creator') ?></h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <h3>Последние зарегистрировавшиеся</h3>
        <ul id="userblockid">
            <?= \app\widgets\UsersWidget::widget(['tpl' =>'admingallery', 'usertype' => 'painter', 'order' => (['created_at' => SORT_DESC])]) ?>
        </ul>
    </div>
    <div class="col-sm-6">
        <h3>Популярные художники</h3>
        <ul id="userblockid">
            <?= \app\widgets\UsersWidget::widget(['tpl' =>'admingallery', 'usertype' => 'painter', 'order' => ['sales' => SORT_DESC]]) ?>
        </ul>

    </div>
</div>
    <h1>События</h1>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3>Вывод денег</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventslist', 'etype' => 'casherequest']) ?>
        </div>
        <div class="col-sm-4">
            <h3>Смена статуса</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventslist', 'etype' => 'casherequest']) ?>
        </div>
        <div class="col-sm-4">
            <h3>Последние события</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventslist', 'etype' => ['casherequest','rmoney','user']]) ?>
        </div>
    </div>
</div>
