<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

use app\models\Products;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]);
        $galery = json::decode($model->photos); //декодим json с массивом галереи
        ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <h1><?= $model->title ?></h1>
        </div>
        <div class="col-md-4">
            <?= Html::img( '/'.$galery[0]['filepath'].$galery[0]['filename'], ['class' => 'img-responsive', 'height' => 'auto', 'width' => 'auto', 'alt' => $this->title, 'title' => $this->title]) ?>
        </div>
        <div class="col-md-8">
            <ul>
                <?php
                //dump($galery);
                ?>

                <li><strong>Автор: </strong><?= Html::encode($model->user->name) ?></li>
                <li><strong>Файл: </strong><?= $model->file ?></li>
                <li><strong>Путь: </strong><?= $model->project_path ?></li>
                <li><strong>Файл: </strong><?=  $galery[0]['filepath'].$galery[0]['filename']  ?></li>

            </ul>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'title',
            'file',
            'tags',
            [
                'format'  => 'html',
                'label' => 'Фото галерея',
                'value' => function($model) {;
                    $photos = json::decode($model->photos);
                    foreach ($photos as $photo) {
                        if ($photo['number'] != 0) {$photolist .= Html::img('/'.$photo['filepath'].'200_200_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '150', 'style' => 'margin: 5px', 'width' => '150', 'alt' => $model->title, 'title' => $model->title]);}
                        //$photolist = Html::img('/'.$photo['filepath'].'100_100_'.$photo['filename'], ['class' => 'img-responsive1', 'height' => '100', 'width' => '100', 'alt' => $model->title, 'title' => $model->title]);
                    }
                    return $photolist;
                },
            ],
            'price',
            'themes',
            'limit',
            'hits',
            'sales',
            'created_at',
        ],
    ]) ?>

</div>
