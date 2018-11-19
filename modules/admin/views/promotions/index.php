<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\PromotionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Promotions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="promotions-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //Pjax::begin(); ?>
    <?php Pjax::begin([ 'id' => 'refreshactions', 'class' => 'refreshactions']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Promotions', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'action_title',
            //'id',
            //'created_at',
            'action_start',
            'action_end',
            [
                'headerOptions' => ['width' => '100'],
                'format'  => 'raw',
                'label' => 'Статус',
                //'attribute' => 'status',
                'value' => function($model){
                    return \app\widgets\PromoNoteWidget::widget(['actionId' => $model->id]);
                },
            ],
            //'action_title',
            //'action_percent',
            //'action_descr:ntext',
            //'action_catergories',
            //'action_userroles',
            //'action_mailtext:ntext',
            //'action_autor',
            //'action_state',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
