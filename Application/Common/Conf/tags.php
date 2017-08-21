<?php

return [
    'app_begin' => ['Behavior\CheckLangBehavior'],

    'app_init' => [
        'Snowair\Think\Behavior\HookAgent',
    ],
];
