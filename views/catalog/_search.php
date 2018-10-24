<?php

use app\models\Catprod;
use app\models\Tags;
use app\models\Themsprod;
use app\models\User;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['/catalog/show'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            //'onchange' => 'this.form.submit()'
        ],
    ]); ?>

    <?php //  $form->field($model, 'id') ?>

    <?php //  $form->field($model, 'title') ?>

    <div class="row">
        <div class="col-md-3">
            <?=
            $form->field($model, 'category')->widget(Select2::classname(), [
                'data' => Catprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                'options' => [
                    'placeholder' => 'Category',
                    'onchange' => 'this.form.submit()',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('');
            ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'themes')->widget(Select2::classname(), [
                'data' => Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                'options' => [
                    'placeholder' => 'Theme',
                    'onchange' => 'this.form.submit()',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('');
            ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'user_id')->widget(Select2::classname(), [
                'data' => User::find()->where(['role' => 'Painter'])->orWhere(['role' => 'Creator'])->select(['username', 'id'])->indexBy('id')->orderBy(['sales' => SORT_DESC])->column(),
                'options' => [
                    'placeholder' => 'Painter',
                    'onchange' => 'this.form.submit()',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('');
            ?>
        </div>

        <div class="col-md-3">
            <?=
            $form->field($model, 'tags')->widget(Select2::classname(), [
                'data' => Tags::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                'options' => [
                    'placeholder' => 'Tag',
                    'onchange' => 'this.form.submit()',
                    'multiple' => false
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('');
            ?>
        </div>

    </div>


    <?php // $form->field($model, 'photos') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'themes') ?>

    <?php // echo $form->field($model, 'limit') ?>

    <?php // echo $form->field($model, 'hits') ?>

    <?php // echo $form->field($model, 'sales') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php ActiveForm::end(); ?>

</div>
