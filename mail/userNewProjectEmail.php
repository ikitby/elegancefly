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
use yii\helpers\Url;
$user = \app\models\User::findOne($product->user_id);
if (empty($user->photo)) {
    $userphoto = Html::img(Url::base(true)."/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
} else {
    $userphoto = Html::img(Url::base(true)."/images/user/user_{$user['id']}/50_50_{$user['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($user['name']) ? $user['name'] : $user['username']), 'title' => Html::encode(($user['name']) ? $user['name'] : $user['username'])]);
}
?>
<h1>Новый проект на сайте!</h1>
<table>
    <tr>
        <td>
            <a href="<?= Url::base(true).yii\helpers\Url::to(['/painters/user', 'alias' => $user['username']]) ?>">
                <?= $userphoto ?>
            </a>
        </td>
        <td>
            <h3><?= Html::encode($user->username) ?> разместил новый проект на сайте</h3><br/>
        </td>
    </tr>
</table>
<?php
$image = json_decode($product->photos);
?>
<hr/>
<table style="width: 100%">
    <tr>
        <td>
            <img src="<?= Url::base(true).'/'.$imageProject ?>" width="300px" height="auto" alt="Новый проект" />
        </td>
    </tr>
</table>
<br/>
<br/>
Письмо носит информационный характер и высылается до <br/>
публикации проекта. Cразу же после удачной загрузки проекта на сайт.
<br/>

