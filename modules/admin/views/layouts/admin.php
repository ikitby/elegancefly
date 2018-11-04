<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Cart;
use app\models\User;
use app\widgets\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AdminAssets;
//use app\assets\AppAsset;

AdminAssets::register($this);
//AppAsset::register($this);

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
<!-- Header -->
<div id="header">

    <div class="top">
        <nav id="nav">

            <div class="usermenu">
                <?= \Yii::$app->view->renderFile('@app/widgets/views/_newactions.php') ?>
                <?php // $this->render('_newactions') ?>
                <ul class="">
                    <li class=""><a href="<?= Url::to('/admin/users') ?>">Пользователи</a></li>
                    <li class=""><a href="<?= Url::to('/admin/pages') ?>">Статичные страницы</a></li>
                    <li class=""><a href="<?= Url::to('/admin/promotions') ?>">Акции</a></li>
                </ul>
                <ul>
                    <li class=""><a href="<?= Url::to('/admin/themsprod') ?>">Управление темами</a></li>
                    <li class=""><a href="<?= Url::to('/admin/tags') ?>">Управление тегами</a></li>
                    <li class=""><a href="<?= Url::to('/rbac/user') ?>">Управление правами</a></li>
                    <hr/>
                    <li class=""><a href="<?= Url::to('/') ?>">Фронт сайта</a></li>

                </ul>

        </nav>
    </div>

    <div class="bottom">

                <?php
                print Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'user btn btn-link logout']
                    )
                    . Html::endForm() ?>

    </div>

</div>


<div class="wrap" id="main">

    <?php
    NavBar::begin([
        'brandLabel' => '<img class="img-responsive" src="/images/logo.png" alt="EleganceFly" title="EleganceFly">',
        'brandUrl' => '/admin',
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            //['label' => 'Home', 'url' => ['/site/index']],
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
            <div class="col-md-12">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
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

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
