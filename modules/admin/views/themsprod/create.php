<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Themsprod */

$this->title = 'Create Themsprod';
$this->params['breadcrumbs'][] = ['label' => 'Themsprods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="themsprod-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
