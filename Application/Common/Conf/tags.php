<?php

return [
    'app_init' => [
        'Snowair\Think\Behavior\HookAgent',
    ],

    'app_begin' => [
        'Behavior\CheckLangBehavior',
        'Home\Behavior\AuthBehavior',
    ],
];
