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
С вашего счета PAC были списаны средства в размере <h2><?= $data['amount'] ?>$</h2>
<br>
<br>
Остаток на счету: <h2><?= $data['current_balance']-$data['amount'] ?>$</h2>





