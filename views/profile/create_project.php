<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = 'Create Project';
$this->params['breadcrumbs'][] = ['label' => 'Catalog', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_submitfile', [
        'model' => $model,
    ]) ?>

    <h1>Правила загузки</h1>
    <p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p><p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p><p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p><p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p><p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p><p>
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
        Правила загузкиПравила загузкиПравила загузкиПравила загузкиПравила загузки
    </p>


</div>
