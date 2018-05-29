<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'name',
            'auth_key',
            'password_hash',
            //'password_reset_token',
            //'email:email',
            //'status',
            //'created_at',
            //'updated_at',
            //'usertype',
            //'photo',
            //'birthday',
            //'country',
            //'languages',
            //'fbpage',
            //'vkpage',
            //'inpage',
            //'percent',
            //'state',
            //'role',
            //'rate',
            //'balance',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
