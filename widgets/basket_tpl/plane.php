<?php
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
<a href="<?= Url::to(['cart/favorite', 'id' => $this->prod_id]) ?>" data-method="post" type="button" class="glyphicon glyphicon-heart btn btn-default btn-xs pull-right"></a>
</span>
<a href="<?= Url::to(['cart/add', 'id' => $this->prod_id]) ?>" type="button" data-id = "<?= $this->prod_id ?>" class="btn btn-default btn-md btn-block add-to-cart">Купить</a>
<br />