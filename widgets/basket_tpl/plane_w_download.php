<?php
use yii\helpers\Url;
?>
<?php
//if (Products::allowPurchased($this->prod_id)) :
if ($this->allowpurchased) :
?>
<?php /*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
 */ ?>
    <h4 class="pull-left"><?= $this->price ?>$</h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->limit) ? $this->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['cart/add', 'id' => $this->prod_id]) ?>" type="button"
        <?= ($this->allowpurchased) ? "" : 'disabled="disabled"' ?>
            data-id = "<?= $this->prod_id ?>" class="btn btn-<?= ($this->allowpurchased) ? "primary" : 'default' ?> btn-block add-to-cart">
             Купить</button>
    <br />
<?php
else:
?>
    <h4 class="pull-left"><?= Yii::$app->formatter->asShortSize($this->file_size) ?></h4>
    <h4 class="pull-right"><?= $this->count ?>/<?= ($this->limit) ? $this->limit : "&infin;" ?></h4>
    <button href="<?= Url::to(['download/project', 'id' => $this->prod_id]) ?>" type="button"
            data-id = "<?= $this->prod_id ?>" class="btn btn-success btn-block project-download">
            <span class="glyphicon glyphicon-download-alt"></span> Скачать!</button>
    <br />
<?php
endif;