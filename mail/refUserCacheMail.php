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
Администратор не одобрил ваш запрос ввода денег с песонального счета PAC.
<br/>
<br/>
Свяжитесь с администратором для уточнения причин.
<br/>
