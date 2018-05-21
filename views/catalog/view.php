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
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <h1><?= $model->title ?></h1>
        </div>
        <div class="col-md-4">
            <?= $model->id ?>

        </div>
        <div class="col-md-8">
            <ul>
                <?php
                $galery = json::decode($model->photos); //декодим json с массивом галереи
                dump($galery);
                ?>

                <li><strong>Автор: </strong><?= Html::encode($model->user->name) ?></li>
                <li><strong>Файл: </strong><?= $model->file ?></li>
                <li><strong>Путь: </strong><?= $model->project_path ?></li>
                <li><strong>Файл: </strong><?php  dump ($model->photos);  ?></li>

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
            'photos',
            'price',
            'themes',
            'limit',
            'hits',
            'sales',
            'created_at',
        ],
    ]) ?>

</div>
