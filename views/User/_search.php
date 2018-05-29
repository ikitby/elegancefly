<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'usertype') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'languages') ?>

    <?php // echo $form->field($model, 'fbpage') ?>

    <?php // echo $form->field($model, 'vkpage') ?>

    <?php // echo $form->field($model, 'inpage') ?>

    <?php // echo $form->field($model, 'percent') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'balance') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
