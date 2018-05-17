<?php

use yii\helpers\Html;
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
    <?php

    ?>
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
