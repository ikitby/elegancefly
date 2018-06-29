<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = 'Update project: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Мои проекты/', 'url' => ['/profile/myprojects']];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="products-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    if (empty($model->limit)) {
        print $this->render('_edit', [
            'model' => $model,
        ]);
    } else {
        header('/profile/myprojects');
    }
    ?>

</div>
