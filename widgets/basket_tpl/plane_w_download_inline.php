<?php
use yii\helpers\Url;
?>
<?php
if ($this->state == 1) :
/*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
 */ ?>
    <h4 class="pull-left file_price">
        <span class="desc">Price</span>
        <?= $this->product->price ?><span>$</span>
    </h4>

    <button href="<?= Url::to(['cart/add', 'id' => $this->product->id]) ?>" type="button"
        <?= ($this->product) ? "" : 'disabled="disabled"' ?>
            data-id = "<?= $this->product->id ?>" class="btn btn-<?= ($this->product) ? "primary" : 'default' ?> add-to-cart btn-lg">
        Add to cart</button>
    <h4 class="pull-right copylimit">
        <span class="desc">Copy/limit</span>
        <?= $this->count ?>/<?= ($this->limit) ? $this->product->limit : "&infin;" ?>
    </h4>
<?php
elseif ($this->state == 2):
?>
    <h4 class="pull-left file_size">
        <span class="desc">Filesize</span>
        <?= Yii::$app->formatter->asShortSize($this->product->file_size) ?>
    </h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->product->id]) ?>" type="button"
            data-id = "<?= $this->product->id ?>" class="btn btn-success project-download btn-lg">
            <span class="glyphicon glyphicon-download-alt"></span> Download!</button>
    <h4 class="pull-right copylimit">
        <span class="desc">Copy/limit</span>
        <?= $this->count ?>/<?= ($this->product->limit) ? $this->limit : "&infin;" ?>
    </h4>
<?php
elseif ($this->state == 3):
    ?>
    <h4 class="pull-left file_size">
        <span class="desc">Filesize</span>
        <?= Yii::$app->formatter->asShortSize($this->product->file_size) ?>
    </h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->product->id]) ?>" type="button"
            data-id = "<?= $this->product->id ?>" class="btn btn-success project-download btn-lg">
        <span class="glyphicon glyphicon-user"></span> Download!</button>
    <h4 class="pull-right copylimit">
        <span class="desc">Copy/limit</span>
        <?= $this->count ?>/<?= ($this->product->limit) ? $this->limit : "&infin;" ?>
    </h4>
    <?php
elseif ($this->state == 0):
?>
    <h4 class="pull-left file_size">
        <span class="desc">Filesize</span>
        <?= Yii::$app->formatter->asShortSize($this->product->file_size) ?>
    </h4>
    <button href="#" type="button"
            class="btn btn-default btn-lg" disabled="disabled">
        <span class="glyphicon glyphicon-check"></span> Sales!</button>
    <h4 class="pull-right copylimit">
        <span class="desc">Copy/limit</span>
        <?= $this->count ?>/<?= $this->limit ?>
    </h4>
<?php
endif;