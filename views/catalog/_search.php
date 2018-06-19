<?php

use app\models\Catprod;
use app\models\Themsprod;
use app\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatalogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'user_id')->dropdownList(User::find()->where(['role' => 'Painter'])->select(['name', 'id'])->indexBy('id')->orderBy(['sales' => SORT_DESC])->column(), ['prompt' => 'Painter...'])->label('Художник') ?>

    <?= $form->field($model, 'category')->dropdownList(Catprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(), ['prompt' => 'Category...'])->label('Категория') ?>

    <?= $form->field($model, 'themes')->dropdownList(Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(), ['prompt' => 'Thema...'])->label('Тематика') ?>

    <?php // echo $form->field($model, 'themes') ?>

    <?php //$form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'photos') ?>

    <?php // echo $form->field($model, 'project_info') ?>

    <?php // echo $form->field($model, 'project_path') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'themes') ?>

    <?php // echo $form->field($model, 'limit') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'sales') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'state')->checkbox() ?>

    <?php // echo $form->field($model, 'deleted')->checkbox() ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
