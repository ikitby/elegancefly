<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Promotions */

$this->title = 'Create Promotions';
$this->params['breadcrumbs'][] = ['label' => 'Promotions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
