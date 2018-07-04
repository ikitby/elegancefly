<?php

use app\models\Themsprod;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photos')->fileInput(['extensions' => ['zip'], 'maxSize' => 1024])->label('Архив проекта') ?>

    <?= $form->field($model, 'themes')->checkboxList(Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column())->label('Тематика') ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->widget(MaskMoney::classname(), [
        'pluginOptions' => [
            'allowNegative' => false
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
