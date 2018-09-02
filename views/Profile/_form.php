<?php

use app\models\Countries;
use app\models\User;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

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

    <?=
    $form->field($model, 'country')->widget(Select2::classname(), [
        'data' => Countries::find()->select(['country', 'id'])->indexBy('id')->orderBy(['country' => SORT_ASC])->column(),
        'options' => [
            'placeholder' => 'Select country',
            'multiple' => false
        ],
        'pluginOptions' => [
        ],
    ])->label('Country');
    ?>
    <?php if (User::Can('haveSocial')):?>

        <?= $form->field($model, 'fbpage')->textInput()->label('Facebook')?>

        <?= $form->field($model, 'vkpage')->textInput()->label('Vkontakte')?>

        <?= $form->field($model, 'inpage')->textInput()->label('Instagram')?>

        <?= $form->field($model, 'tumblrpage')->textInput()->label('Tumblr')?>

        <?= $form->field($model, 'youtubepage')->textInput()->label('Youtube')?>

    <? endif; ?>

    </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
