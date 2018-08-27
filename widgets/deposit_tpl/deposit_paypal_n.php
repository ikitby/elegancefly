<?php
use kartik\widgets\TouchSpin;

?>

<div class="">
    <div class="col-md-122 depowidget">
        <div class="input-groupn" style="display: none;">

            <?= TouchSpin::widget([
                'name' => 'contrast',
                'pluginOptions' => [
                    'initval' => $this->data,
                    'min' => 0,
                    'max' => 15,
                    'placeholder' => '...'],
            ]) ?>

            <div class="input-group-btnn input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success send_deposit">Send</button>
                    <button class="btn btn-danger deposit_hide" type="button">✕</button>
                </span>
            </div>
        </div>
        <button type="button" class="btn btn-primary deposit_show"><span class="glyphicon glyphicon-usd"></span> Add funds</button>
    </div>
</div>

