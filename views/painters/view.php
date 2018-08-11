<?php

use app\models\Transaction;
use app\models\User;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

if (empty($painter->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($painter->name) ? $painter->name : $painter->username), 'title' => Html::encode(($painter->name) ? $painter->name : $painter->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$painter->id}/{$painter->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($painter->name) ? $painter->name : $painter->username), 'title' => Html::encode(($painter->name) ? $painter->name : $painter->username)]);
}

$this->title = $painter->name;
$this->params['breadcrumbs'][] = ['label' => 'Painters', 'url' => '/painters'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode(($painter->name) ? $painter->name : $painter->username) ?></h1>

    <div class="row">
        <div class="col-md-4">
            <?= $userphoto ?>
        </div>
        <div class="col-md-8">
            <?php
            echo StarRating::widget([
                'name' => 'rating_'.$painter->id.'',
                'id' => 'input-'.$painter->id.'',
                'value' => $painter->rate,
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

            <ul>
                <li><strong>Профиль: </strong>
                    <?php

                    $roles = Yii::$app->authManager->getRolesByUser($painter->id);
                    foreach ($roles as $role) : ?>
                        <span class="label label-primary"><?= Html::encode($role->name) ?></span>
                    <?php endforeach; ?>

                </li>
                <li><strong>Имя: </strong><?= Html::encode($painter->name) ?></li>
                <li><strong>Country: </strong><?= $painter->userCountry->country ?></li>
                <li><strong>Работ: </strong><a href="<?= Url::to(['/catalog/painter', 'painter' => $painter->username]) ?>"><?= Html::encode(User::getUserProjectsCount($painter->id)) ?></a></li>
                <li><strong>Продаж: </strong><?= $painter->sales ?></li>
                <li><strong>Продаж: </strong><?= Transaction::getUserSales($painter->id) ?></li>
            </ul>
        </div>
    </div>

    <?php
    /*DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'status',
            'created_at',
            'updated_at',
            'usertype',
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
    ]) */
    ?>

</div>
