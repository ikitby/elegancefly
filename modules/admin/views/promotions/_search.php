<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\PromotionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promotions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'action_start') ?>

    <?= $form->field($model, 'action_end') ?>

    <?= $form->field($model, 'action_title') ?>

    <?php // echo $form->field($model, 'action_percent') ?>

    <?php // echo $form->field($model, 'action_descr') ?>

    <?php // echo $form->field($model, 'action_catergories') ?>

    <?php // echo $form->field($model, 'action_userroles') ?>

    <?php // echo $form->field($model, 'action_mailtext') ?>

    <?php // echo $form->field($model, 'action_autor') ?>

    <?php // echo $form->field($model, 'action_state') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
