<?php

return [

    'adminEmail' => 'info@ikit.by',
    'user.passwordResetTokenExpire' => 3600,
    'supportEmail' => 'info@elegancefly.com',
    'emailActivation' => true, //Активация пользователя через email

    'minLimitCasheMoney' => 50, //Минимальная сумма разрешенного вывода налички
    'requestDelay' => 60, //Глобальная задержка на действия пользователя

    'owlLoopFront'  => false, //Петля кареселек на главке

    'limitcatsIDs' => [2,9] //Категории с ограниченным доступом для торцов

];
