<?php

use app\models\Transaction;
use app\models\User;
use app\widgets\DepositWidget;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View Тесть */
/* @var $model app\models\User */

if (empty($model->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$model->id}/{$model->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
}

$this->title = Html::encode($model->username);
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = Html::encode($this->title);
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

<div class="row">
    <div class="col-md-4">
        <div style="text-align: center;">
        <h2><?= Html::encode($this->title) ?></h2>

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

        <?= $userphoto ?>
        </div>

        <ul class="userprop">

            <li><strong>Profile: </strong>
                <?php foreach (User::Roles($model->id) as $role) : ?>
                    <span class="label label-primary"><?= $role->name; ?></span>
                <?php endforeach; ?>
            </li>
            <li><strong>Registered: </strong><?= Yii::$app->formatter->asDate($model->created_at) ?></li>
            <?php if (User::Can('createProject')):?><li><strong>Works: </strong><a href="<?= Url::to(['/catalog/painter', 'painter' => Html::encode($model->username)]) ?>"><?= Html::encode(User::getUserProjectsCount($model->id)) ?></a></li><?php endif; ?>
            <?php if (User::Can('createProject')):?><li><strong>Sales: </strong><?= Html::encode(Transaction::getUserSales($model->id)) ?></li><?php endif; ?>
            <?php if ($model->name) : ?>
            <li><strong>Name: </strong><?= Html::encode($model->name) ?></li>
            <?php endif; ?>
            <?php if ($model->birthday) : ?><li><strong>Birthday: </strong><?= Yii::$app->formatter->asDate($model->birthday) ?></li><?php endif; ?>
            <?php if ($model->userCountry->country) : ?><li><strong>Country: </strong><?= $model->userCountry->country ?></li><?php endif; ?>
            <li><strong>Email: </strong><?= Html::mailto($model->email) ?></li>

        </ul>
        <ul class="userpropsocicons">
            <?php if ($model->vkpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/vk.png" alt="VK" title="'.$model->username.' VK">', Url::to($model->vkpage), ['target' => '_blank']) ?></li><?php endif;?>
            <?php if ($model->fbpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/facebook.png" alt="Facebook" title="'.$model->username.' Facebook">', Url::to($model->fbpage), ['target' => '_blank']) ?></li><?php endif;?>
            <?php if ($model->inpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/instagram.png" alt="Instagram" title="'.$model->username.' Instagram">', Url::to($model->inpage), ['target' => '_blank']) ?></li><?php endif;?>
            <?php if ($model->tumblrpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/tumblr.png" alt="Tumblr" title="'.$model->username.' Tumblr">', Url::to($model->tumblrpage), ['target' => '_blank']) ?></li><?php endif;?>
            <?php if ($model->youtubepage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/youtube.png" alt="Youtube" title="'.$model->username.' Youtube">', Url::to($model->youtubepage), ['target' => '_blank']) ?></li><?php endif;?>
        </ul>

        <div id="balancewrapp">
        <div class="balance">
            Balance:
            <h3><?= Transaction::getUserBalance($model->id) ?>$</h3>
        </div>
        <div class="depos">
            <?= DepositWidget::widget(['tpl' =>'deposit_paypal_n', 'data' => '5']) ?>
        </div>
        <div class="edit">
            <?= Html::a('Edit', ['edit'], ['class' => 'btn btn-primary pull-right']) ?>
        </div>
    </div>

    </div>
    <div class="col-md-8">

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 1, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 2, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 9, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 4, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 6, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?= \app\widgets\VitrinaWidget::widget(['category_id' => 8, 'user_id' => $model->id, 'loop' => false, 'items_count' => 1000]) ?>

        <?php
/*
        dump(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
        dump(Yii::$app->authManager->checkAccess(Yii::$app->user->id,'createProject'));
        dump(User::Can('createProject'))
*/
        ?>

    </div>

</div>

</div>
