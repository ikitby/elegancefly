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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            //'photos',
            [
                'headerOptions' => ['width' => '50'],
                'format'  => 'html',
                'label' => 'Img',
                'value' => function($model) {
                    $photo = json_decode($model->photos);
                    return Html::img('/'.$photo[0]->filepath.'/100_100_'.$photo[0]->filename, ['class' => 'img-responsive', 'width' => '50px']);
                },
            ],
            'title',
            [
                'headerOptions' => ['width' => '150'],
                'format'  => 'date',
                'label' => 'Upload date',
                'attribute' => 'created_at',
                'value' => function($model) {
                    return $model->created_at;
                },

            ],
            [
                'headerOptions' => ['width' => '150'],
                'format'  => 'html',
                'label' => 'Count',
                'attribute' => 'Count',
                'value' => function($model) {
                    $sales = Transaction::getSales($model->id);
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
                    $sales = Transaction::getSales($model->id);
                    $sales['sum'] = (!empty($sales['sum'])) ? $sales['sum'] : 0;
                    return '<h3>'.$sales['sum'].'$</h3>';
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
