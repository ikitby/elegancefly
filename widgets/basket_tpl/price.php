<?php

use app\models\Products;
use yii\helpers\Url;
?>
<span class="priceonly">
    <span class="pricevalue">
        <?=
        $this->product->price
        ?>
    </span>
    <span class="nominal">
        $
    </span>
</span>