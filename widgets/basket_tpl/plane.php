<?php

use app\models\Products;
use yii\helpers\Url;
?>
<span>
    <span class="pricevalue">
        <?=
        $this->product->price
        ?>
    </span>
    <span class="nominal">
        $
    </span>
</span>
<span class="pull-right">
<button href="<?= Url::to(['cart/favorite', 'id' => $this->product->id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
<button href="<?= Url::to(['cart/add', 'id' => $this->product->id]) ?>" type="button"
    <?= (Products::allowPurchased($this->product->id)) ? "" : 'disabled="disabled"' ?>
        data-id = "<?= $this->product->id ?>" class="btn btn-<?= (Products::allowPurchased($this->product->id)) ? "primary" : 'defoult' ?> btn-block add-to-cart">Купить</button>
<br />