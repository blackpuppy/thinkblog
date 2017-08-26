<?php

return [
    'app_init' => [
        'Snowair\Think\Behavior\HookAgent',
        // 'Home\Behavior\AuthBehavior',   // too early, constants not set
    ],

    // 'module_check' => [
    //     'Home\Behavior\DebugBehavior',
    // ],

    // 'path_info' => [
    //     'Home\Behavior\DebugBehavior',
    // ],

    // 'url_dispatch' => [
    //     'Home\Behavior\DebugBehavior',
    //     // 'Home\Behavior\AuthBehavior',
    // ],

    'app_begin' => [
        'Behavior\CheckLangBehavior',
        // 'Home\Behavior\AuthBehavior',   // too late, route changed
    ],

    'action_begin' => [
        'Home\Behavior\AuthBehavior',
    ],
];
