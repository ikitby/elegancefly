<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Cart;
use app\widgets\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

$cartsumm = Cart::getCartsummWS();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="is-preload">
<?php $this->beginBody() ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.1';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- Header -->
<div id="header">

    <div class="top">
        <nav id="nav">
            <!-- Social Icons -->
            <div id="userblock">
                <?= \app\widgets\UserWidget::widget(['tpl' =>'user']) ?>
            </div>
        </nav>
    </div>

    <div class="bottom">
        <div class="bottommenu">
            <ul>
                <li><a href="<?= Url::to('/about') ?>">About us</a></li>
            </ul>
        </div>
        <div id="sociconsblock">
            <a href="https://www.facebook.com/groups/elegancefly/" target="_blank"><img class="img-responsive" src="/images/icons/facebook.svg" alt="EleganceFly facebook" title="EleganceFly facebook"></a>
            <a href="https://www.instagram.com/elegancefly/" target="_blank"><img class="img-responsive" src="/images/icons/instagram.svg" alt="EleganceFly instagram" title="EleganceFly instagram"></a>
            <a href="https://vk.com/elegancefly" target="_blank"><img class="img-responsive" src="/images/icons/vk.svg" alt="EleganceFly instagram" title="EleganceFly vk"></a>
        </div>
    </div>

</div>


<div class="wrap" id="main">

    <?php
    NavBar::begin([
        'brandLabel' => '<img class="img-responsive" src="/images/logo.png" alt="EleganceFly" title="EleganceFly">',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Catalog', 'url' => ['/catalog']],
            ['label' => 'Painters', 'url' => ['/painters']],
            ['label' => 'Cart <span class="label label-warning"><span class="cartsummres">'.$cartsumm.'</span>$</span>', 'url' => ['/cart']],
            Yii::$app->user->isGuest ? (['label' => 'Signup', 'url' => ['/site/signup']]) : "",
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    ?>
    <?php
    NavBar::end();
    ?>

    <div class="con tainer">
        <div class="row">
        <div class="col-md-12 col-lg-9">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
        <div class="col-md-12 col-lg-3">

            <h3>Топ художников<span class="label label-info"></span></h3>
            <ul id="userblockid">
                <?= \app\widgets\UsersWidget::widget(['tpl' =>'gallery', 'usertype' => ['Painter','Creator'], 'limit' => 10]) ?>
            </ul>

        </div>

            <?php
            if( Yii::$app->session->hasFlash('success') ): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
            <?php endif; ?>

        </div>

    </div>
</div>


<div id="footer">

</div>

<?php
Modal::begin([
    //'header' => '<h4 class="modal-title">Info</h4>',
    'id' => 'InfoModal',
]);
Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4 class="modal-title">Profile upgrade</h4>',
    'footer' => '<button type="button" id="imPainter" style="margin: 0 auto;display: inherit;" class="btn btn-success btn-lg">Я - Художник!</button>',
    'id' => 'upgProfile',
]);

Modal::end();
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
