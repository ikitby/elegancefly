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
$username = ($user['name'] ? $user['name'] : $user['username']);
?>

<p>Здравствуйте, <?= $username ?>.</p>
<p>На сайте <?= Yii::$app->name ?> проводится акция.</p>
<p><h3><?= $promo->action_title ?></h3></p>
<p>Период проведения акции: с <?= $promo->action_start ?> по <?= $promo->action_end ?></p>
<p>Скидка по акции: <h2><?= $promo->action_percent ?>%</h2></p>
<p><?= $promo->action_mailtext ?></p>
