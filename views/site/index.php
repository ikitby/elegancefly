<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 1]) ?>

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 2]) ?>

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 9]) ?>

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 4]) ?>

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 6]) ?>

            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 8]) ?>

    </div>
</div>
