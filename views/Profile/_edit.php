<?php

use app\models\Catprod;
use app\models\Products;
use app\models\Tags;
use app\models\Themsprod;
use app\models\Transaction;
use app\models\User;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">
<?php
/*
    if(!$model->save()) {
        print_r($model->errors);
    }
*/
?>

    <?php
    $galery = json::decode($model->photos); //декодим json с массивом галереи
    ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <?= Html::img('/'.$galery[0]['filepath'].'400_400_'.$galery[0]['filename'], ['class' => 'img-responsive']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <div style="text-align: center;">
                        <?php if ($model->price && $model->limit > 0) : ?>
                            Current price (limit project): <h3><?= $model->price ?>$</h3><?php endif; ?>
                        <?php
                        if (Transaction::getProdSales($model->id) == 0 && User::Can('canSetLimitProject'))
                        {
                            print Html::a('Set limit', ['#'], ['class' => 'btn btn-info btn-md limitproject', 'data-id' => $model->id]);
                        }
                        ?>
                    </div>

                <?= ($model->limit > 0) ? false : $form->field($model, 'price')->textInput() ?>

                <?php //$form->field($model, 'category')->dropdownList(Catprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column())->label('Category') ?>

                <?php
                $distate = true;
                if (User::Can('canResaleForResale')) { $distate = false; }

                print $form->field($model, 'category')->widget(Select2::classname(), [
                'data' => Catprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                'options' => [
                    'placeholder' => 'Category',

                    'options' => [
                        2 => ['disabled' => $distate],
                    ],

                    'multiple' => false,
                ],
                'pluginOptions' => [
                'allowClear' => true
                ],
                ])->label('Category'); ?>

                <?= $form->field($model, 'themes')->checkboxList(Themsprod::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column())->label('Themes') ?>

                <?=
                $form->field($model, 'tags')->widget(Select2::classname(), [
                    'data' => Tags::find()->select(['title', 'id'])->indexBy('id')->orderBy(['id' => SORT_ASC])->column(),
                    'options' => [
                        'placeholder' => 'Добавьте метки проекту',
                        'multiple' => true
                    ],
                    'pluginOptions' => [
                        'tags' => true,
                        'tokenSeparators' => [','],
                        'maximumInputLength' => 20
                    ],
                ])->label('Метки');
                ?>
                <?= $form->field($model, 'project_info')->textarea() ?>

            </div>
        </div>
    </div>
    <div class="col-md-12 bg-warning">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    Modal::begin([
        'header' => '<h4 class="modal-title">Сделать проект эксклюзивом</h4>',
        'id' => 'UniqProject',
    ]);
    Modal::end();
    ?>
</div>
