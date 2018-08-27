<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form" id="newproject_form">

    <?php

    echo FileInput::widget([
        'name' => 'photos',
        //'language' => 'ru',

        'options' => [
            'multiple' => false,
        ],
        'pluginOptions' => [
            'showPreview' => false,
            'showUpload' => true,
            'previewFileType' => 'zip',
            //'uploadUrl' => Url::to(["/catalog/create"])
            'uploadUrl' => Url::to(["/catalog/ajaxfile"])
        ]
    ]);


    ?>

</div>
