<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = 'Update Products: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты/', 'url' => ['/profile/myprojects']];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_edit', [
        'model' => $model,
    ]) ?>

</div>
