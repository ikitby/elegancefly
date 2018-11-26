<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
/*
if(!$model->save()) {
    print_r($model->errors);
}
*/
?>

<div class="site-signup">
    <div class="well" style="max-width: 400px; margin: 0 auto 10px;">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <div class="controls">
            <h1><?= Html::encode($this->title) ?></h1>
            <hr>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control input-lg']) ?>
            <?= $form->field($model, 'email')->textInput(['class' => 'form-control input-lg']) ?>
            <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control input-lg']) ?>
            <?= $form->field($model, 'password_repeat')->passwordInput(['class' => 'form-control input-lg']) ?>

            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'signup-button']) ?>
        </div>
            <?php ActiveForm::end(); ?>

            <?php
            if ($model->scenario === 'emailActivation'):
            ?>
                <i><h6>На указанный email будет отправлено письмо для активации</h6></i>
            <?php endif; ?>
    </div>
</div>