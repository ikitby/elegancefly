<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput() ?>

    <?= $form->field($model, 'file')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'project_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'themes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'themes_index')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hits')->textInput() ?>

    <?= $form->field($model, 'state')->checkbox() ?>

    <?= $form->field($model, 'deleted')->checkbox() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
