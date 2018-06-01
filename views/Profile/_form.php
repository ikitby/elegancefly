<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

if (empty($model->photo)) {
    $userphoto = Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
} else {
    $userphoto = Html::img("/images/user/user_{$model->id}/{$model->photo}", ['class' => 'img-responsive', 'alt' => Html::encode(($model->name) ? $model->name : $model->username), 'title' => Html::encode(($model->name) ? $model->name : $model->username)]);
}
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
    <div class="col-md-4">
        <?= $userphoto ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'photo')->fileInput(['maxlength' => true])->label('Фото')?>
    </div>
    <div class="col-md-12">
    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), ['options' => ['placeholder' => 'Enter birth date ...'], 'pluginOptions' => ['autoclose'=>true, 'format' => 'dd.M.yyyy', 'todayHighlight' => true]]); ?>

    <?= $form->field($model, 'country')->textInput() ?>

    <?= $form->field($model, 'languages')->textInput() ?>

    <?= $form->field($model, 'fbpage')->textInput() ?>

    <?= $form->field($model, 'vkpage')->textInput() ?>

    <?= $form->field($model, 'inpage')->textInput() ?>

    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
