<?php

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

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
<div class="col-md-12">
    <?= Html::a('Edit', ['edit'], ['class' => 'btn btn-primary pull-right']) ?>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $userphoto ?>
        <?php
        echo StarRating::widget([
            'name' => 'rating_'.$model->id.'',
            'id' => 'input-'.$model->id.'',
            'value' => $model->getUserRating($model->id)[0]['rating'],
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
        ]); ?>
        <?= $model->getUserRating($model->id)[0]['rating'] ?>

    </div>
    <div class="col-md-8">
        <ul>
            <li><strong>Статус: </strong><?= Html::encode($model->usertype) ?></li>
            <li><strong>Логин: </strong><?= Html::encode($model->usertype) ?></li>
            <li><strong>Email: </strong><?= Html::mailto($model->email) ?></li>
            <li><strong>Имя: </strong><?= Html::encode($model->name) ?></li>
            <li><strong>Страна: </strong><?= Html::encode($model->country) ?></li>
            <li><strong>Статус: </strong><?= Html::encode($model->languages) ?></li>
        </ul>
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
