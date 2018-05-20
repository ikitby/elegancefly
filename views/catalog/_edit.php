<?php

use app\models\Themsprod;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">
<?php

    if(!$model->save()) {
        print_r($model->errors);
    }

?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'themes')->widget(Select2::classname(), [
        'data' => Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
        'options' => ['placeholder' => 'Выберите тематику', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 10
        ],
    ])->label('Выберите тематику'); ?>

    <?php // $form->field($model, 'themes')->checkboxList(Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column())->label('Тематика') ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'limit')->textInput() ?>

    <?= $form->field($model, 'sales')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
