<?php

use app\models\Transaction;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Statistic';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //Pjax::begin(); ?>
    <?php echo $this->render('_searchstatistic', ['model' => $searchModel]);?>
<br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'photos',
            [
                'headerOptions' => ['width' => '60'],
                'format'  => 'html',
                'label' => 'Img',
                'value' => function($model) {
                    $photo = json_decode($model->photos);
                    return Html::img('/'.$photo[0]->filepath.'/100_100_'.$photo[0]->filename, ['class' => 'img-responsive', 'width' => '80px']);
                },
            ],
            [
                'headerOptions' => ['width' => 'auto'],
                'format'  => 'html',
                'label' => 'Name',
                'attribute' => 'title',
                'value' => function($model) {
                    return '<h4>'.$model->title.'</h4>';
                },

            ],
            [
                'headerOptions' => ['width' => '150'],
                'format'  => 'html',
                'label' => 'Upload date',
                'attribute' => 'created_at',
                'value' => function($model) {
                    $created_at = $model->created_at;
                    $created_at = date("Y-m-d", strtotime($created_at));
                    return '<h4>'.$created_at.'</h4>';
                },

            ],
            [
                'headerOptions' => ['width' => '150'],
                'format'  => 'html',
                'label' => 'Count',
                'attribute' => 'Count',
                'value' => function($model) {
                    $from_date = Yii::$app->request->get('from_date'); //Мнинимальная дкта
                    $to_date = Yii::$app->request->get('to_date'); //Максимальная дата
                    $sales = Transaction::getSales($model->id, $from_date, $to_date);
                    $sales['count'] = (!empty($sales['count'])) ? $sales['count'] : 0;
                    return '<h4>'.$sales['count'].'</h4>';
                },
            ],
            [
                'headerOptions' => ['width' => '150'],
                'format'  => 'html',
                'label' => 'Sum',
                'attribute' => 'transaction.amount',
                'value' => function($model) {
                    $from_date = Yii::$app->request->get('from_date'); //Мнинимальная дкта
                    $to_date = Yii::$app->request->get('to_date'); //Максимальная дата
                    $sales = Transaction::getSales($model->id, $from_date, $to_date);
                    $sales['sum'] = (!empty($sales['sum'])) ? $sales['sum'] : 0;
                    return '<h4>'.$sales['sum'].'$</h4>';
                },
            ],


            //'title',
            //'category',
            //'file_size',
            //'tags',
            //'photos',
            //'project_info',
            //'project_path',
            //'price',
            //'themes',
            //'themes_index',
            //'limit',
            //'hits',
            //'sales',
            //'rating',
            //'tatng_votes',
            //'state:boolean',
            //'deleted:boolean',
            //'created_at',
            //'active_promo',

        ],
    ]); ?>
    <?php //Pjax::end(); ?>
</div>
