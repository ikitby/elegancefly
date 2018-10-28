<?php

use app\models\Transaction;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StatisticSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['/profile/statistic'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php // $form->field($model, 'user_id') ?>
    <div class="row1">
    <div class="col-md-12 well">
        <div class="col-md-6">
        <?php

        $from_date = Yii::$app->request->get('from_date'); //Мнинимальная дкта
        $to_date = Yii::$app->request->get('to_date'); //Максимальная дата

        $mindate = (!empty($from_date))? $from_date : newdate(Transaction::find()->select(['created_at', 'id'])->where(['action_user' => Yii::$app->user->id, 'type' => 1])->indexBy('created_at')->min('created_at'));
        //$maxdate = newdate(Transaction::find()->select(['created_at', 'id'])->indexBy('created_at')->max('created_at'));
        $maxdate = (!empty($to_date))? $to_date : newdate(date("Y-m-d"));

        echo DatePicker::widget([
            'name' => 'from_date',
            'value' => $mindate,
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'to_date',
            'value2' => $maxdate,
            'pluginOptions' => [
                'autoclose'=>true,
                'todayHighlight' => true,
                'todayBtn' => true,
                'format' => 'yyyy-mm-dd'
            ],
            'options' => [
                //'onchange' => 'this.form.submit()'
            ],
        ]);

        function newdate($date) {
            $date = strtotime($date); // переводит из строки в дату
            $date = date("Y-m-d", $date);
            return $date;
        }
        ?>
        </div>

    <div class="col-md-6">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    </div>
    </div>
</div>
    <?php ActiveForm::end(); ?>
</div>
