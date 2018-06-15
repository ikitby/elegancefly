<?php

use app\models\Transaction;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

if (empty($model->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$model->id}/{$model->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
}

$this->title = Html::encode('Payments');
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

<h1><?= $this->title ?></h1>

    Текущий баланс:
    <h3><?= Transaction::getUserBalance(Yii::$app->user->id) ?>$</h3>

<div class="row payments">
    <table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>#ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Balance</th>
    </tr>
    </thead>
    <tbody>
        <?php
        foreach ($payments as $payment) :
            $typeclass = "";
            switch ($payment->type) {
                case 0:
                    $typeclass = 'warning';
                    break;
                case 1:
                    $typeclass = 'success';
                    break;
                case 3:
                    $typeclass = 'info';
                    break;
            }
              //dump($payment);
         //   dump($payment->actionUser);
         //   dump($payment->sourcePayment);
         //   dump($payment->actionProd);

        ?>
        <tr class="<?= $typeclass ?>">
            <td><strong>#</strong><?= $payment->id ?></td>
            <td>
                <span class="glyphicon glyphicon-calendar"></span>
                <?= Yii::$app->formatter->asDate($payment->created_at, 'medium') ?><br>
                <?= Yii::$app->formatter->asTime($payment->created_at, 'medium') ?>
            </td>
            <td>
                <h4><?= $payment->amount ?>$</h4>
            </td>
            <td><h4><?= $payment->c_balance ?>$</h4></td>
        </tr>
        <?php
        endforeach;
        ?>
    </tbody>
    </table>
</div>

    <?php /*
  DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'status',
            'created_at',
            //'updated_at',
            //'usertype',
            'photo',
            'birthday',
            'country',
            'languages',
            'fbpage',
            'vkpage',
            'inpage',
            'percent',
            'state',
            'role',
            'rate',
            'balance',
        ],
    ])
 */
 ?>

</div>
