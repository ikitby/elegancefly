<?php
/*
use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AdminAsset;
use app\models\User;
use app\models\Apiaries;

//AdminAsset::register($this);

$hiverscount = User::find()->count();
$apicount = Apiaries::find()->count();
*/
?>
<div class="admin-default-index">
    <h1><?= $this->context->action->uniqueId ?></h1>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file:<br>
        <code><?= __FILE__ ?></code>
    </p>
</div>
