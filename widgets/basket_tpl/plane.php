<?php

use app\models\Products;
use yii\helpers\Url;
?>
<span>
    <span class="pricevalue">
        <?=
            $this->price
        ?>
    </span>
    <span class="nominal">
        $
    </span>
</span>
<span class="pull-right">
<button href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></button>
</span>
<button href="<?= Url::to(['cart/add', 'id' => $this->prod_id]) ?>" type="button"
    <?= (Products::allowPurchased($this->prod_id)) ? "" : 'disabled="disabled"' ?>
        data-id = "<?= $this->prod_id ?>" class="btn btn-<?= (Products::allowPurchased($this->prod_id)) ? "primary" : 'defoult' ?> btn-block add-to-cart">Купить</button>
<br />