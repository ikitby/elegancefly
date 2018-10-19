<?php

use app\models\Transaction;
use app\models\User;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $painter app\models\User */

if (empty($painter->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($painter['name']) ? $painter['name'] : $painter['username']), 'title' => Html::encode(($painter['name']) ? $painter['name'] : $painter['username'])]);
} else {
    $userphoto = Html::img("/images/user/user_{$painter['id']}/{$painter['photo']}", ['class' => 'img-responsive', 'alt' => Html::encode(($painter['name']) ? $painter['name'] : $painter['username']), 'title' => Html::encode(($painter['name']) ? $painter['name'] : $painter['username'])]);
}

$this->title = ($painter['name']) ? $painter['name'] : $painter['username'];
$this->params['breadcrumbs'][] = ['label' => 'Painters', 'url' => '/painters'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="row">
        <div class="col-md-4">
            <div style="text-align: center;">
                <h2><?= Html::encode($this->title) ?></h2>

                <?php

                if (!Yii::$app->authManager->getRolesByUser($painter['id'])["User"]) :
                    echo StarRating::widget([
                        'name' => 'rating_'.$painter['id'].'',
                        'id' => 'input_'.$painter['id'].'',
                        'value' => ($painter['rate']) ? $painter['rate'] : 0,
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

                    <?= (empty($painter['rate'])) ? "0" : $painter['rate'] ?>
                    (<?= (empty($painter['rate_c'])) ? "0" : $painter['rate_c'] ?>)
                <?php endif; ?>

                <?= $userphoto ?>
            </div>

            <ul class="userprop">
                <li><strong>Profile: </strong>
                    <?php foreach (User::Roles($painter['id']) as $role) : ?>
                        <span class="label label-primary"><?= $role->name; ?></span>
                    <?php endforeach; ?>
                </li>
                <li><strong>Registered: </strong><?= Yii::$app->formatter->asDate($painter->created_at) ?></li>
                <?php if (User::Can('createProject')):?><li><strong>Works: </strong><a href="<?= Url::to(['/catalog/painter', 'painter' => Html::encode($painter->username)]) ?>"><?= Html::encode(User::getUserProjectsCount($painter->id)) ?></a></li><?php endif; ?>
                <?php if (User::Can('createProject')):?><li><strong>Sales: </strong><?= Html::encode(Transaction::getUserSales($painter->id)) ?></li><?php endif; ?>
                <?php if ($painter->name) : ?>
                    <li><strong>Name: </strong><?= Html::encode($painter->name) ?></li>
                <?php endif; ?>
                <li><strong>Birthday: </strong><?= Yii::$app->formatter->asDate($painter->birthday) ?></li>
                <?php if ($painter->userCountry->country) : ?> <li><strong>Country: </strong><?= $painter->userCountry->country ?></li><?php endif; ?>
                <li><strong>Email: </strong><?= Html::mailto($painter->email) ?></li>

            </ul>
            <ul class="userpropsocicons">
                <?php if ($painter->vkpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/vk.png" alt="VK" title="'.$painter->username.' VK">', Url::to($painter->vkpage), ['target' => '_blank']) ?></li><?php endif;?>
                <?php if ($painter->fbpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/facebook.png" alt="Facebook" title="'.$painter->username.' Facebook">', Url::to($painter->fbpage), ['target' => '_blank']) ?></li><?php endif;?>
                <?php if ($painter->inpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/instagram.png" alt="Instagram" title="'.$painter->username.' Instagram">', Url::to($painter->inpage), ['target' => '_blank']) ?></li><?php endif;?>
                <?php if ($painter->tumblrpage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/tumblr.png" alt="Tumblr" title="'.$painter->username.' Tumblr">', Url::to($painter->tumblrpage), ['target' => '_blank']) ?></li><?php endif;?>
                <?php if ($painter->youtubepage) : ?><li><?= Html::a('<img class="img-responsive" src="/images/icons/youtube.png" alt="Youtube" title="'.$painter->username.' Youtube">', Url::to($painter->youtubepage), ['target' => '_blank']) ?></li><?php endif;?>
            </ul>

        </div>
        <div class="col-md-8">

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 1, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 2, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 9, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 4, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 6, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

                <?= \app\widgets\VitrinaWidget::widget(['category_id' => 8, 'user_id' => $painter->id, 'loop' => false, 'items_count' => 1000]) ?>

            <?php
            /*
                    dump(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
                    dump(Yii::$app->authManager->checkAccess(Yii::$app->user->id,'createProject'));
                    dump(User::Can('createProject'))
            */
            ?>

        </div>
    </div>

    <?php
    /*DetailView::widget([
        'model' => $painter,
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
