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
Новый пользователь, <?= Html::encode($user->username) ?> зарегистировался на сайте<br/>
<br/>
дата регистрации: <?= Yii::$app->formatter->asDate($user->created_at) ?>
<br/>
<br/>
<hr/>
<?= Html::a('Вы можете самостоятельно активировать пользователя по данной ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/activate-account', 'key' =>$user->password_reset_token
        ]
    )); ?>
