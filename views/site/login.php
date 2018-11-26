<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <div class="well" style="max-width: 400px; margin: 0 auto 10px;">

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            //'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'control-label'],
            ],
        ]); ?>

        <div class="controls">
            <h1><?= Html::encode($this->title) ?></h1>
            <hr>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'form-control input-lg'])->label('Логин или Email') ?>

        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control input-lg']) ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "{input} {label}\n{error}",
        ]) ?>

        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg btn-block', 'name' => 'login-button']) ?>
        </div>
        <i><h6>
            If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
        </h6></i>

        <?php ActiveForm::end(); ?>
    </div>

</div>
