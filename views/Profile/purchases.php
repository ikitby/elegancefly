<?php

use app\models\DownloadProject;
use app\models\Transaction;
use app\widgets\BasketWidget;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Html::encode('My purchases');
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

<h1><?= $this->title ?></h1>

    <?php Pjax::begin(); ?>
<div class="row payments">
    <table class="table table-striped table-hover">
    <thead>
    <tr>
        <th>#ID</th>
        <th>Source</th>
        <th>Actions</th>
    </tr>
    </thead>

    <tbody>
        <?php
        foreach ($purchases as $purchase) :
            $typeclass = "";
            $typedescr = "";

              //dump($payment);
         //   dump($payment->actionUser);
         //   dump($payment->sourcePayment);
         //   dump($payment->actionProd);

        ?>
        <tr class="<?= $typeclass ?>">
            <td><strong> #</strong><?= $purchase->actionProd->id ?></td>
            <td>
                <h4 class="media-heading">
                    <a href="<?= Url::to(["/catalog/category", "catalias" => $purchase->actionProd->catprod->alias, "id" => $purchase->actionProd->id]) ?>">
                        <?= $purchase->actionProd->title ?>
                    </a>

                </h4>
                <span class="glyphicon glyphicon-calendar" title="Tooltip on top"></span>
                <?= Yii::$app->formatter->asDate($purchase->created_at, 'medium') ?> <?= Yii::$app->formatter->asTime($purchase->created_at, 'medium') ?>


            </td>
            <td>
                <?php
                if ($image = json_decode($purchase->actionProd->photos)[0]) : ?>
                <a href="<?= Url::to(["/catalog/category", "catalias" => $purchase->actionProd->catprod->alias, "id" => $purchase->actionProd->id]) ?>">
                    <?= Html::img('/'.$image->filepath.'100_100_'.$image->filename, ['title' => $purchase->actionProd->title]) ?>
                </a>
                <?php endif; ?>
            </td>
            <td>
                <?= BasketWidget::widget([
                    'template' =>'plane_w_download',
                    'prod_id' => $purchase->actionProd->id
                ])?>
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
