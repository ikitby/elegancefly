<?php

use app\models\User;
use app\models\Userevent;
use yii\widgets\Pjax;

$userid = Yii::$app->user->id;

if (Yii::$app->authManager->getRolesByUser($userid)["Admin"]) :
/*
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $.pjax.reload({container : '#topiccontainer'}); }, 3000);
});
JS;
$this->registerJs($script);
*/
    Pjax::begin(['id' =>'topiccontainer']);
    ?>

        <div class="topinfoicons" style="text-align: center;">
            <?php
            $profileUpCount = Userevent::find()->where(['event_progress' => 0, 'event_type' => 'profileupdate'])->count();
            $getCasheCount = Userevent::find()->where(['event_progress' => 0, 'event_type' => 'casherequest'])->count();
            ?>
            <?php if (User::Can('canApprowPainterMail') && $profileUpCount):?>
                <a class="btn btn-info btn-xs" href="/admin">Profile UP&nbsp;<span class="badge pull-right"><?= $profileUpCount ?></span></a>
            <?php endif; ?>
            <?php if (User::Can('canReceiveCasheMail') && $getCasheCount):?>
                <a class="btn btn-warning btn-xs" href="/admin">Get Cashe&nbsp;<span class="badge pull-right"><?= $getCasheCount ?></span></a>
            <?php endif; ?>

        </div>

    <?php
    Pjax::end();
    endif; ?>

