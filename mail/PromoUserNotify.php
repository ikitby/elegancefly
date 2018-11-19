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

$catslist = '';
$i = 1;
$count = count($cats);
foreach ($cats as $category)
{
    $catslist .= ($i < $count) ? '<h4>'.$category{'title'} . ',</h4> ' : '<h4>'.$category{'title'}.'</h4>';
    $i++;
}

?>

<p>Здравствуйте, <?= $username ?>.</p>
<p>На сайте <?= Yii::$app->name ?> проводится акция.</p>
<p><h3><?= $promo->action_title ?></h3></p>
<p>Период проведения акции: с <?= $promo->action_start ?> по <?= $promo->action_end ?></p>
<p>Скидка по акции: <h2><?= $promo->action_percent ?>%</h2></p>
<p>В акции принимают участие проекты из разделов: <?= $catslist ?></p>
<p><?= $promo->action_mailtext ?></p>
<hr>
Отметьте в личном кабинете среди своих проектов те, что будут принимать участие в данной промо-акции.