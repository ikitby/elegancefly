<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 01.07.2018
 * Time: 15:26
 */
use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<div id="limitform" class="row">
    <?php $form = ActiveForm::begin([
        'action' => '/profile/setlimit',
        //'enableAjaxValidation' => true,
        //'enableClientValidation' => false,
        //'validationUrl' => Url::to(['/profile/validate']),
        'method' => 'post',
        'options' => [

        ],
        // остальные опции ActiveForm
    ]);
    ?>
    <div class="col-md-6">
        <?= $form->field($model, 'price')->textInput()->label('Price') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'limit')->dropdownList([
            1 => 1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20],['prompt' => 'No limit...'])->label('Limit') ?>
    </div>
    <div class="modal-footer">
        <?= $form->field($model, 'id')->hiddenInput(['value' => $model->id])->label(false) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <?= Html::submitButton('Make Exclusive!', ['class' => 'btn btn-success']) ?>
    </div>
    <?php $form = ActiveForm::end(); ?>
</div>
