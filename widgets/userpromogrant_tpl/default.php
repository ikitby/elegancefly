<?php

use yii\helpers\StringHelper;
use yii\helpers\Url;

if ($this->product['active_promo'] == $this->promo['pid'] || $this->product['active_promo']) : ?>
    <a href="" type="button" class="btn btn-default btn-sm prodpromodel" data-id="<?= $this->product['id'] ?>" data-pid="<?= $this->promo["pid"] ?>">Отклонить акцию!</a>
<?php else: ?>
<span rel="popover"
      class="active"
      data-content='
        <h5><?= StringHelper::truncate($this->promo["promotitle"],150,'...'); ?></h5>
        <h6>Скидка: <span class="promoprocent"><?= $this->promo["procent"] ?></span>
        <br>Текущая цена: <span class="promooldprice"><?= $this->promo["oldPrice"] ?><span>$</span></span>
        <br>Акционная цена: <span class="promoprice"><?= $this->promo["price"] ?><span>$</span></span></h6>
        <small>Старт: <?= $this->promo["promosrart"] ?></small><br>
        <small>Стоп: <?= $this->promo["promostop"] ?></small><br>'
      data-original-title="Проект может принять участие в Акции!">

    <a href="" type="button" class="btn btn-danger btn-sm prodpromoset" data-id="<?= $this->product['id'] ?>" data-pid="<?= $this->promo["pid"] ?>">Принять акцию! <span class="glyphicon glyphicon-exclamation-sign"></span></a>

        </span>
<?php endif; ?>