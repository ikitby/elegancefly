<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 22.07.2018
 * Time: 13:12
 * @var $ths->yii\web\View
 * $var $user app\models\User
 */

use yii\helpers\Html;

?>
Здравствуйте, <?= Html::encode($user->username) ?>.
<br/>
<br/>
Администратор одобрил ваш запрос на повышение аккаунта. Возможности вашего профиля расширеы в соответствии с новым уровнем.
<br/>
<br/>
Спасибо за то, что вы с нами.