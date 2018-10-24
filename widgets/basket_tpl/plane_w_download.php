<?php
use yii\helpers\Url;
?>
<?php
if ($this->state == 1) :
/*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
 */
$sale = $this->saleprice;
$sale_procent = $sale['procent'];
$oldprice = $sale['oldPrice'];
?>
    <?php if ($sale_procent) : ?><span class="salew1"><span class="salew2"><span class="small">SALE</span><?= $sale_procent ?></span></span><?php endif; ?>
    <h4 class="pull-left file_price"><?= $this->product->price ?><span>$</span></h4>
    <?php if ($oldprice) : ?><span class="oldproce" title="Price without discount: <?= $oldprice ?>$"> <?= $oldprice ?><span>$</span> </span><?php endif; ?>
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