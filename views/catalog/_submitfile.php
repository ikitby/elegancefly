<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form" id="newproject_form">

    <?php
/*
    $script = "
    $('form').submit(function(e){
        var formData = new FormData($(this)[0]);        
        $.ajax({
            type: 'post',
            url:'". Url::to(['/catalog/create'])."',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
            }
        })  
        e.preventDefault();
         
        return false;
    });     
    ";
    $this->registerJs($script);*/
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'photos')->fileInput(['extensions' => ['zip'], 'maxSize' => 1024])->label('Архив проекта') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-info']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
