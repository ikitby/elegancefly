<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php
    // echo $this->render('_search', ['model' => $searchModel]);
    ?>

    <p>
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format'  => 'html',
                'label' => 'Фото',
                'value' => function($model) {
                    $photos = json::decode($model->photos);
                    return '<a href="'.Url::to(["view", "id" => $model->id]).'" type="button" class="">'.Html::img('/'.$photos[0]['filepath'].'200_200_'.$photos[0]['filename'], ['class' => 'img-responsive1', 'height' => '200', 'width' => '200', 'alt' => $model->title, 'title' => $model->title]).'</a>';
                },
            ],
            'title',
            'file',
            'tags',
            //'photos',
            /*[
                'format'  => 'html',
                'label' => 'Фото галерея',
                'value' => function($model) {
                    $photos = json::decode($model->photos);
                    foreach ($photos as $photo) {
                        if ($photo['number'] != 0) {$photolist .= Html::img('/'.$photo['filepath'].'100_100_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '100', 'width' => '100', 'alt' => $model->title, 'title' => $model->title]);}
                        //$photolist = Html::img('/'.$photo['filepath'].'100_100_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '100', 'width' => '100', 'alt' => $model->title, 'title' => $model->title]);
                    }
                    return $photolist;
                },
            ],*/
            //'price',
            //'themes',
            //'limit',
            //'hits',
            //'sales',
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
