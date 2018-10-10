<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <h1><?= Html::encode($model->title) ?></h1>
        <hr>
        <?= Yii::$app->formatter->asHtml($model->text) ?>
    </div>
</div>
