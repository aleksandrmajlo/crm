<?php
// статусы заказов
// status 1 - в работе
// status 3 -  завершено положительно
// status 4 -  завершено отрицательно
// status 6-  работник отказался от работы

// type 1 - основное
// type 2 - дополнительное
return [

    'orderstatus'=>[
        '1'=>'work',
        '3'=>'done',
        '4'=>'failed',
        '5'=>'Set Admin Free',
        '6'=>'Refuse User',

    ],
    'typeorder'=>[
        '1'=>'base',
        '2'=>'additional',
    ]

];