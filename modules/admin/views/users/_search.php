<?php

use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'username') ?>

    <?php // $form->field($model, 'role')->dropDownList(ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description')) ?>

    <?=
    $form->field($model, 'role')->widget(Select2::classname(), [
        //'data' => User::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
        'data' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
        'options' => [
            'placeholder' => 'Тип пользователя',
            'onchange' => 'this.form.submit()',
            'multiple' => false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('');
    ?>
    <?php //$form->field($model, 'name') ?>

    <?php //$form->field($model, 'auth_key') ?>

    <?=0 //$form->field($model, 'password_hash') ?>

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

    <?php // echo $form->field($model, 'tumblrpage') ?>

    <?php // echo $form->field($model, 'youtubepage') ?>

    <?php // echo $form->field($model, 'percent') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'rate') ?>

    <?php // echo $form->field($model, 'rate_c') ?>

    <?php // echo $form->field($model, 'sales') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
