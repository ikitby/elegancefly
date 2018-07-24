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
Привет, <?= Html::encode($user->username) ?> <br/><br/>

<?= Html::a('Для активации аккаунта перейтите по данной ссылке.',
        Yii::$app->urlManager->createAbsoluteUrl(
            [
                '/activate-account', 'key' =>$user->password_reset_token
            ]
        )); ?>
<br/><br/>
Или перейдите по ссылке в браузере: <br/><br/><?= Yii::$app->urlManager->createAbsoluteUrl(['/activate-account', 'key' =>$user->password_reset_token])?>

