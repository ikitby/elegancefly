<?php

use app\models\DownloadProject;
use app\models\Products;
use yii\helpers\Url;
?>
<?php
if (Products::allowPurchased($this->prod_id)) :
?>
<?php /*
    <span class="pull-right">
        <button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
 */ ?>
    <button href="<?= Url::to(['cart/add', 'id' => $this->prod_id]) ?>" type="button"
        <?= (Products::allowPurchased($this->prod_id)) ? "" : 'disabled="disabled"' ?>
            data-id = "<?= $this->prod_id ?>" class="btn btn-<?= (Products::allowPurchased($this->prod_id)) ? "primary" : 'defoult' ?> btn-block add-to-cart">
            <span class="badge pull-left"><?= $this->price ?>$</span> Купить</button>
    <br />
<?php
else:
?>
        <button href="<?= Url::to(['download/project', 'id' => $this->prod_id]) ?>" type="button"
                data-id = "<?= $this->prod_id ?>" class="btn btn-success btn-block project-download">
                <span class="badge pull-left"><?= Products::getFileSize($this->prod_id) ?></span>
                <span class="glyphicon glyphicon-download-alt"></span> Скачать!</button>

    <br />
<?php
endif;