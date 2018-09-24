<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 22.07.2018
 * Time: 13:12
 * @var $ths->yii\web\View
 * $var $user app\models\User
 */

use app\models\Transaction;
use yii\helpers\Html;

?>
Пользователь, <?= Html::encode($user->username) ?> достиг баланса <?= Transaction::getUserBalance($user->id); ?>$ на счету<br/>
и просит их обналичить.
<br/>