<?php
/**
 * Created by PhpStorm.
 * User: Grey
 * Date: 17.11.2018
 * Time: 10:52
 */
use yii\helpers\Html;
use yii\helpers\Url;

if ($this->sended == 1) :
?>
    <a href="/" type="button"
       disabled="disabled" class="btn btn-default btn-block add-to-cart disabled">Отправлено</a>
<?php
else :
?>
<a href="/" type="button" data-promoid="<?= $this->actionId ?>" id="actionid_<?= $this->actionId ?>" class="btn btn-success btn-block btn-success sendpromonotify">Отправить</a>
<?php
endif;
?>