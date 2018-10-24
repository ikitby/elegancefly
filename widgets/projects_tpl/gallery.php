<?php
use yii\helpers\Html;
use yii\helpers\Url;

$photo = json_decode($project->photos);
if (empty($project)) return '<center>Empty<center>';
?>
<li>
    <a href="<?= Url::to(['/catalog/category', "catalias" => $project->catprod->alias, "id" => $project->id]) ?>">
        <?= Html::img('/'.$photo[0]->filepath.'100_100_'.$photo[0]->filename, ['class' => 'img-responsive', 'title' => $project->title]) ?>
    </a>
</li>