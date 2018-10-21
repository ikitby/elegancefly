<?php

use app\models\Promotions;
use yii\helpers\Url;

//Шаблон цены с кнопками загрузки и скидками/

?>
<?php
if ($this->state == 1) :
/*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>

 */

$sale = Promotions::getSale($this->product);
$price = Promotions::getSalePrice($this->product);

?>

    <h4 class="pull-left file_price"><?= $this->product->price ?>$</h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->limit) ? $this->product->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['cart/add', 'id' => $this->product->id]) ?>" type="button"
        <?= ($this->product) ? "" : 'disabled="disabled"' ?>
            data-id = "<?= $this->product->id ?>" class="btn btn-<?= ($this->product) ? "primary" : 'default' ?> btn-block add-to-cart">
             Add to cart</button>
    <br />
<?php
elseif ($this->state == 2):
?>
    <h4 class="pull-left file_size"><?= Yii::$app->formatter->asShortSize($this->product->file_size) ?></h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->product->limit) ? $this->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->product->id]) ?>" type="button"
            data-id = "<?= $this->product->id ?>" class="btn btn-success btn-block project-download">
            <span class="glyphicon glyphicon-download-alt"></span> Download!</button>
    <br />
<?php
elseif ($this->state == 3):
    ?>
    <h4 class="pull-left file_size"><?= Yii::$app->formatter->asShortSize($this->product->file_size) ?></h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->product->limit) ? $this->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->product->id]) ?>" type="button"
            data-id = "<?= $this->product->id ?>" class="btn btn-success btn-block project-download">
        <span class="glyphicon glyphicon-user"></span> Download!</button>
    <br />
    <?php
elseif ($this->state == 0):
?>
    <h4 class="pull-left file_size"><?= Yii::$app->formatter->asShortSize($this->product->file_size) ?></h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= $this->limit ?></h4>
    <button href="#" type="button"
            class="btn btn-default btn-block" disabled="disabled">
        <span class="glyphicon glyphicon-check"></span> Sales
        !</button>
    <br />
<?php
endif;