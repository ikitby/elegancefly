<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="rowline">
            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 1]) ?>
        </div>

        <div class="rowline">
            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 2]) ?>
        </div>

        <div class="rowline">
            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 4]) ?>
        </div>

        <div class="rowline">
            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 6]) ?>
        </div>

        <div class="rowline">
            <?= \app\widgets\VitrinaWidget::widget(['category_id' => 8]) ?>
        </div>


    </div>
</div>
