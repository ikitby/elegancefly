<?php

use app\models\Products;
use app\models\Transaction;
use kartik\widgets\StarRating;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Html::encode('My projects');
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-view">

<h1><?= $this->title ?></h1>

    <?php Pjax::begin(); ?>
<div class="row payments">

        <?php
        foreach ($projects as $project) :

            $typeclass = "";
            $typedescr = "";

            switch ($project->state) {
                case 0:
                    $typeclass = 'success';
                    $typedescr = '<span class="state_'.$project->id.' label label-warning">Не продается</span>';
                    break;
                case 1:
                    $typeclass = 'warning';
                    $typedescr = '<span class="state_'.$project->id.'  label label-success">Продается</span>';
                    break;
                case 3:
                    $typedescr = '<span class="state_'.$project->id.' label label-info">Уник!</span>';
                    $typeclass = 'info';
                    break;
            }

              //dump($payment);
         //   dump($payment->actionUser);
         //   dump($payment->sourcePayment);
         //   dump($payment->actionProd);

        ?>
        <div id="project_<?= $project->id ?>" class="col-md-12 <?= $typeclass ?>">
            <div class="row">
                <div class="col-md-2">
                    <?php
                    if ($image = json_decode($project->photos)[0]) : ?>
                        <a href="<?= Url::to(["/catalog/category", "catalias" => $project->catprod->alias, "id" => $project->id]) ?>">
                            <?= Html::img('/'.$image->filepath.'200_200_'.$image->filename, ['title' => $project->title, 'class' => 'img-responsive']) ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <h4 class="media-heading"><a href="<?= Url::to(["/catalog/category", "catalias" => $project->catprod->alias, "id" => $project->id]) ?>"><?= $project->title ?></a> <strong>#</strong><?= $project->id ?></h4>
                    <div class="clr"></div>
                    <?= StarRating::widget([
                        'name' => 'rating_'.$project->id.'',
                        'id' => 'input-'.$project->id.'',
                        'value' => $project->rating,
                        'attribute' => 'rating',
                        'pluginOptions' => [
                            'size' => 'xs',
                            'stars' => 5,
                            'step' => 1,
                            'readonly' => true,
                            'disabled' => true,
                            'showCaption' => false,
                            'showClear'=>false
                        ]]);
                    ?>

                    <?= Yii::$app->formatter->asDate($project->created_at, 'medium') ?>
                    <?= Yii::$app->formatter->asTime($project->created_at, 'medium') ?>
                    <div class="col-md-12 actions">
                        <br/>
                        <?= Html::a('Update', ['/profile/updateproject', 'id' => $project->id], ['class' => 'btn btn-primary btn-xs']) ?>
                        <?= Html::a('Publish', ['#'], ['class' => 'state_'.$project->id.' btn btn-success btn-xs publishproject', 'data-id' => $project->id]) ?>
                        <?= Html::a('Set limit', ['/profile/setlimit', 'id' => $project->id], ['class' => 'btn btn-info btn-xs setlimitproject', 'data-id' => $project->id]) ?>
                        <a class="btn btn-danger btn-xs deletemyproject" href="#" data-id="<?= $project->id ?>"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                    </div>
                </div>
                <div class="col-md-2">
                    <h4><?= $project->price ?> $</h4>
                    <span class=""><?= $typedescr ?></span><br>
                    Просмотров: <?= ($project->hits) ? $project->hits : 0 ?><br/>
                    Продаж: <?= Products::getProjectSelling($project->id) ?>/<?= ($project->limit) ? $project->limit : "&infin;" ?>
                </div>



            </div>
            <hr/>
        </div>
        <?php
        endforeach;
        ?>

    <div class="row">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>

</div>
    <?php Pjax::end(); ?>
    <?php /*
  DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'name',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            //'status',
            'created_at',
            //'updated_at',
            //'usertype',
            'photo',
            'birthday',
            'country',
            'languages',
            'fbpage',
            'vkpage',
            'inpage',
            'percent',
            'state',
            'role',
            'rate',
            'balance',
        ],
    ])
 */
 ?>

</div>
