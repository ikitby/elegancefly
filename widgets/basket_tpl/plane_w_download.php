<?php
use yii\helpers\Url;
?>
<?php
//if (Products::allowPurchased($this->prod_id)) :
dump($this->state);
if ($this->state == 1) :
?>
<?php /*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
 */ ?>
    <h4 class="pull-left"><?= $this->product->price ?>$</h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->limit) ? $this->product->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['cart/add', 'id' => $this->product->id]) ?>" type="button"
        <?= ($this->product) ? "" : 'disabled="disabled"' ?>
            data-id = "<?= $this->product->id ?>" class="btn btn-<?= ($this->product) ? "primary" : 'default' ?> btn-block add-to-cart">
             Купить</button>
    <br />
<?php
elseif ($this->state == 2):
?>
    <h4 class="pull-left"><?= Yii::$app->formatter->asShortSize($this->product->file_size) ?></h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->product->limit) ? $this->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->product->id]) ?>" type="button"
            data-id = "<?= $this->product->id ?>" class="btn btn-success btn-block project-download">
            <span class="glyphicon glyphicon-download-alt"></span> Скачать!</button>
    <br />
<?php
endif;