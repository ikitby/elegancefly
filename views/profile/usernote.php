<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View Тесть */
/* @var $model app\models\User */

?>
<div class="popupmessage">
    <p class="bg-info" style="padding: 15px;">Уважаемый <b><?= $user->username ?></b>. Для изменения вашего профиля с <b>User</b> на <b>Painter</b> выполните следующие действия:</p>
    <ul>
        <li>пришлите ваше портфолио на электронный адрес: <?= Html::mailto('info@elegancefly.com') ?></li>
        <li>Подтвердите ваше желание уведомив администратора сайта нажав кнопку ниже.</li>
    </ul>
</div>