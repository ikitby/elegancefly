<?php

use ckarjun\owlcarousel\OwlCarouselWidget;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php// Pjax::begin(); ?>
        <div class="rowusers">
            <?php
            if (!empty($users)) :
                foreach ($users as $user):
                    ?>

                    <div class="col-md-3">
                        <?php
                        print 'photo';
                        ?>
                    </div>

                    <?php
                endforeach;
            else: ?>
                Нет тут ни чего!
            <?php endif;
            ?>
            <div class="row">
                <?= LinkPager::widget(['pagination' => $pagination]) ?>
            </div>
        </div>

        <?php // Pjax::end(); ?>
    </div>
