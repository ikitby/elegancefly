<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php //cho $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           /* [
                'label' => 'ID',
                'attribute' => 'id',
            ],*/
            [
                'headerOptions' => ['width' => '50'],
                'format'  => 'html',
                'label' => 'Фото',
                'value' => function($model) {

                    if (empty($model->photo)) {
                        return '<a href="'.yii\helpers\Url::to(["/admin/users/view", "id" => $model->id]).'">'.Html::img("/images/user/nophoto.png", ['class' => 'img-responsive', 'alt' => Html::encode($model->username), 'title' => Html::encode($model->username)]).'</a>';
                    } else {
                        return '<a href="'.yii\helpers\Url::to(["/admin/users/view", "id" => $model->id]).'">'.Html::img("/images/user/user_{$model->id}/100_100_{$model->photo}", ['class' => 'img-responsive', 'alt' => Html::encode($model->username), 'title' => Html::encode($model->username)]).'</a>';
                    }

                },

            ],
            [
                'headerOptions' => ['width' => '50'],
                'format'  => 'html',
                'label' => 'Username',
                'attribute' => 'username',
                'value' => function($model){
                    $uroles = '';
                    foreach (User::Roles($model->id) as $role) :
                        $uroles .= $model->username.'<br />';
                        $uroles .= '<span class="label label-primary">'.$role->name.'</span>';
                    endforeach;
                    return $uroles;
                },
            ],
            [
                'label' => 'Роль',
                'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
                'attribute' => 'role',
            ],
            /*[
                'label' => 'Картинка',
                'format' => 'image',
                'attribute' => 'photo',
                //'filter' => ['on', 'off'],
                'value' => 'photo',
            ],*/

            [
                //'headerOptions' => ['width' => '90%'],
                'label' => 'Email',
                'format'  => 'email',
                'attribute' => 'email',
            ],
            //'name',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            [
                'headerOptions' => ['width' => '50'],
                'label' => 'Вкл?',
                'attribute' => 'status',
                'filter' => [10 => 'Вкл.', 0 => 'Выкл.'],
            ],
            //'created_at',0
            //'updated_at',
            //'usertype',

            //'birthday',
            //'country',
            //'languages',
            //'fbpage',
            //'vkpage',
            //'inpage',
            //'tumblrpage',
            //'youtubepage',
            //'percent',
            //'state',
            //'role',
            //'rate',
            //'rate_c',
            //'sales',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
