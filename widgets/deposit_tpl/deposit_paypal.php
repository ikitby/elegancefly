<?php
use kartik\widgets\TouchSpin;


?>

<div class="row">
    <div class="col-md-6 depowidget">
        <div class="input-group" style="display: none;">

            <?= TouchSpin::widget([
                'name' => 'contrast',
                'pluginOptions' => [
                    'initval' => $this->data,
                    'min' => 0,
                    'max' => 100,
                    'placeholder' => 'Adjust ...'],
            ]) ?>
            <span class="input-group-btn">
                <button type="button" class="btn btn-success send_deposit"><span class="glyphicon glyphicon-usd"></span> Отправить</button>
                <button class="btn btn-danger deposit_hide" type="button">✕</button>
            </span>
        </div>
        <button type="button" class="btn btn-primary deposit_show"><span class="glyphicon glyphicon-usd"></span> Пополнить счет</button>
    </div>
</div>

