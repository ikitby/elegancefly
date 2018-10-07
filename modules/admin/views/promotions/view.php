<?php

use app\models\Promotions;
use app\widgets\BasketWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Promotions */

$this->title = $model->action_title;
$this->params['breadcrumbs'][] = ['label' => 'Promotions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotions-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'created_at',
            'action_start',
            'action_end',
            'action_title',
            'action_percent',
            'action_descr:ntext',
            'action_catergories',
            'action_userroles',
            'action_mailtext:ntext',
            'action_autor',
            'action_state',
        ],
    ]) ?>
<h3>Работы в акции</h3>
    <div class="row" style="text-align: center;">
    <?php
        $promotions = $model->getPromPod(1);
if (!empty($promotions)) {
    foreach ($promotions as $promotion) :
        $photos = json_decode($promotion->photos);
        $photo = $photos[0]->filepath . '200_200_' . $photos[0]->filename;
        ?>

        <div class="col-sm-2">
            <?= Html::img('/' . $photo, ['class' => 'img-responsive']) ?>
            <h6><?= Html::encode($promotion->title) ?></h6>
            <?= BasketWidget::widget(['template' => 'price', 'product' => $promotion])
            ?>
        </div>
        <?php
    endforeach;
}
else {
    print 'Пусто...';
}
    ?>
    </div>
</div>
