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
                    return '<a href="'.yii\helpers\Url::to(["/admin/users/view", "id" => $model->id]).'">'.$model->username.'</a>
                    <br/><h6 style="margin: 0">'.Yii::$app->formatter->asDate($model->created_at).'</h6>';
                },
            ],

            [
                'headerOptions' => ['width' => '100'],
                'format'  => 'html',
                'label' => 'Роль',
                'attribute' => 'role',
                'value' => function($model){
                    $uroles = '';
                    foreach (User::Roles($model->id) as $role) :
                        $label = 'default';
                        switch ($role->name) {
                            case 'Admin';
                                $label = 'danger';
                            break;
                            case 'Painter';
                                $label = 'primary';
                            break;
                            case 'Creator';
                                $label = 'success';
                            break;
                        }

                        $uroles = '<span class="label label-'.$label.'">'.$role->name.'</span>';

                    endforeach;
                    return $uroles;
                },
                'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),

            ],
            [
                'headerOptions' => ['width' => '100'],
                'format'  => 'html',
                'label' => 'Процент',
                'attribute' => 'percent',
                'filter' =>  User::find()->select('percent')->indexBy('percent')->column(),

            ],

            /*
             * Countries::find()->select(['country', 'id'])->indexBy('id')->orderBy(['country' => SORT_ASC])->column(),
            [
                'label' => 'Роль',
                'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'),
                'attribute' => 'role',
            ],
            */
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
                'format'  => 'html',
                'label' => 'Вкл?',
                'attribute' => 'status',
                'filter' => [10 => 'Активен.', 0 => 'Не активен.'],
                'value' => function($model){
                    $label = 'success';
                    switch ($model->status) {
                        case '0';
                            $label = 'danger';
                            $text = 'Не активен';
                            break;
                        case '10';
                            $label = 'success';
                            $text = 'Активен';
                            break;
                    }
                    return '<span class="label label-'.$label.'">'.$text.'</span>';
                }

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
