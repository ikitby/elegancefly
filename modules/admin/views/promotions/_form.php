<?php

use app\models\Catprod;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Promotions */
/* @var $form yii\widgets\ActiveForm */

$model->action_catergories = explode(",", $model->action_catergories);
$model->action_userroles = explode(",", $model->action_userroles);

?>

<div class="promotions-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'action_title')->textInput(['maxlength' => true]) ?>

    <fieldset>
        <legend>Период действия</legend>
        <div class="row">

            <div class="col-sm-6">
                <?= $form->field($model, 'action_start')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите начало акции ...'],
                    'pluginOptions' => [
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'action_end')->widget(DateTimePicker::classname(), [
                        'options' => ['placeholder' => 'Выберите окончание акции ...'],
                        'pluginOptions' => [
                            'autoclose' => true
                        ]
                    ]) ?>
            </div>

        </div>
    </fieldset>

    <fieldset>
        <legend>Область действия скидки</legend>

    <?= $form->field($model, 'action_catergories')->widget(Select2::classname(), [
        'data' => Catprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
        'options' => [
            'placeholder' => 'Раздел',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Разделы каталога'); ?>

    <?= $form->field($model, 'action_userroles')->widget(Select2::classname(), [
        //'data' => User::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
        'data' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
        'options' => [
            'placeholder' => 'Роли пользователя',
            'multiple' => true
        ],
        'pluginOptions' => [
            'tags' => true,
            'allowClear' => true
        ],
    ])->label('Роль пользователя'); ?>

    </fieldset>

    <?= $form->field($model, 'action_percent')->textInput() ?>

    <?= $form->field($model, 'action_descr')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'inline' => false, //по умолчанию false
        ],
    ]); ?>

    <?= $form->field($model, 'action_mailtext')->widget(CKEditor::className(),[
        'editorOptions' => [
            'preset' => 'basic', //разработанны стандартные настройки basic, standard, full данную возможность не обязательно использовать
            'inline' => false, //по умолчанию false
        ],
    ]); ?>

    <?= $form->field($model, 'action_state')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
