<?php

use app\models\Transaction;
use app\models\User;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

if (empty($model->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$model->id}/{$model->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
}

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
/*
$painter = false;
$roles = Yii::$app->authManager->getRolesByUser($model->id);
foreach ($roles as $role) {
    if ($role->name === 'Admin' || $role->name === 'Admin') {$painter = true;}
}

dump($painter);
*/
?>
<div class="user-view">

    <h1><?= $this->title ?></h1>
<div class="col-md-12">
    <?= Html::a('Edit', ['edit'], ['class' => 'btn btn-primary pull-right']) ?>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $userphoto ?>
        <?php
        if (empty($model->role) || $model->role != 'User') :
        echo StarRating::widget([
            'name' => 'rating_'.$model->id.'',
            'id' => 'input-'.$model->id.'',
            'value' => $model->rate,
            'attribute' => 'rating',
            'pluginOptions' => [
                'size' => 'sm',
                'stars' => 5,
                'step' => 1,
                'readonly' => true,
                'disabled' => true,
                'showCaption' => false,
                'showClear'=>false
            ],
        ]);

        ?>

        <?= $model->rate ?>
        (<?= (empty($model->rate_c)) ? "0" : $model->rate_c ?>)
        <?php endif; ?>


    </div>
    <div class="col-md-8">

        <ul>
            <li><strong>Профиль: </strong><span class="label label-primary"><?= Html::encode($model->role) ?></span>
            </li>
            <li><strong>Логин: </strong><?= Html::encode($model->username) ?></li>
            <li><strong>Email: </strong><?= Html::mailto($model->email) ?></li>
            <li><strong>Имя: </strong><?= Html::encode($model->name) ?></li>
            <?php if (empty($model->role) || $model->role != 'User') : ?>
                <li>
                    <strong>Работ: </strong><a href="<?= Url::to(['/catalog/painter', 'painter' => $model->username]) ?>"><?= Html::encode(User::getUserProjectsCount($model->id)) ?></a>
                </li>
            <li><strong>Продаж: </strong><?= Html::encode(Transaction::getUserSales($model->id)) ?></li>
            <li><strong>Страна: </strong><?= Html::encode($model->country) ?></li>
            <?php endif; ?>
        </ul>
        Баланс:
        <h3><?= Transaction::getUserBalance($model->id) ?>$</h3>
    </div>
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
