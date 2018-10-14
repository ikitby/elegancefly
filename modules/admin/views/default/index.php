<?php

use app\widgets\Alert;
use yii\helpers\Html;
use app\models\User;
use yii\helpers\Url;

//AdminAsset::register($this);

?>
<div class="admin-default-index">
<h1>Пользователи</h1>
<div class="row">

    <div class="container" style="background-color: #212528;color:  #ccc;">
        <div class="col-sm-3" style="text-align: center; color: #eed77d;">
            Пользователей:
            <a style="color: #eed77d;" href="<?= Url::to(['/admin/users']) ?>"><h1><?= User::getUsersCount(['Painter','Creator','User']) ?></h1></a>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Покупателей:
            <a style="color: #ccc;" href="<?= Url::to(['/admin/users', 'UsersSearch[role]' => 'User']) ?>"><h1><?= User::getUsersCount('User') ?></h1></a>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Художников:
            <a style="color: #ccc;" href="<?= Url::to(['/admin/users', 'UsersSearch[role]' => 'Painter']) ?>"><h1><?= User::getUsersCount('Painter') ?></h1></a>
        </div>
        <div class="col-sm-3" style="text-align: center;">
            Творцов:
            <a style="color: #ccc;" href="<?= Url::to(['/admin/users', 'UsersSearch[role]' => 'Creator']) ?>"><h1><?= User::getUsersCount('Creator') ?></h1></a>
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
        <?= Yii::$app->name ?>
        <div class="col-sm-4">
            <h3>Вывод денег</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventslist', 'etype' => 'casherequest']) ?>
        </div>
        <div class="col-sm-4">
            <h3>Смена статуса</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventsreqlist', 'etype' => 'profileupdate']) ?>
        </div>
        <div class="col-sm-4">
            <h3>Последние события</h3>
            <?= \app\widgets\UserEventsWidget::widget(['tpl' =>'eventslist', 'etype' => ['casherequest','rmoney','user','profileupdate','addfunds']]) ?>
        </div>
    </div>
</div>
