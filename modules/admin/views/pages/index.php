<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\PagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pages', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ///'id',
            [
                'headerOptions' => ['width' => '250'],
                'format'  => 'html',
                'label' => 'title',
                'attribute' => 'title',
                'value' => function($model){
                    return '<a href="'.yii\helpers\Url::to(["/admin/pages/update", "id" => $model->id]).'">'.$model->title.'</a>';
                }
            ],
            //'alias',
            //'seo_title',
            //'seo_keyworlds',
            //'seo_desc',
            //'text:ntext',
            [
                ///'headerOptions' => ['width' => '50'],
                'format'  => 'html',
                'label' => 'text',
                'attribute' => 'text',
                'value' => function($model){
                    return StringHelper::truncate(Yii::$app->formatter->asText($model->text), 300, '...');
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
