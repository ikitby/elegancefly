<?php

use app\models\Transaction;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Html::encode('Payments');
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

<h1><?= $this->title ?></h1>

    Текущий баланс:
    <h3><?= Transaction::getUserBalance(Yii::$app->user->id) ?>$</h3>
    <?php Pjax::begin(); ?>
<div class="row payments">
    <table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>#ID</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Balance</th>
        <th>Source</th>
    </tr>
    </thead>

    <tbody>
        <?php
        foreach ($payments as $payment) :
            $typeclass = "";
            $typedescr = "";

            switch ($payment->type) {
                case 0:
                    $typeclass = 'warning';
                    $typedescr = 'Покупка проекта';
                    break;
                case 1:
                    $typeclass = 'success';
                    $typedescr = 'Продажа проекта';
                    break;
                case 3:
                    $typedescr = 'Пополнение счета';
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
            <td>
                <h4><?= $payment->c_balance ?>$</h4>
            </td>
            <td>
                <?= $typedescr ?><br>
                <?php
                if ($image = json_decode($payment->actionProd->photos)[0]) : ?>
                <a href="<?= Url::to(["/catalog/category", "catalias" => $payment->actionProd->catprod->alias, "id" => $payment->actionProd->id]) ?>">
                    <?= Html::img('/'.$image->filepath.'100_100_'.$image->filename, ['title' => $payment->actionProd->title]) ?>
                </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php
        endforeach;
        ?>
    </tbody>

    </table>
    <div class="row">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>

</div>
    <?php Pjax::end(); ?>
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
