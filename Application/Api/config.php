<?php
return [
    'URL_ROUTER_ON'     => true,  //开启路由
    'URL_ROUTE_RULES'   => [ //定义路由规则
        ['api/signup', 'Api/User/signup', '', ['method' => 'post'],
        ['api/login',  'Api/User/login',  '', ['method' => 'post'],
        ['api/logout', 'Api/User/logout', '', ['method' => 'post'],

        ['api/postsapi',       'Api/Post/index',  '', ['method' => 'get']],
        ['api/postsapi',       'Api/Post/create', '', ['method' => 'post']],
        ['api/postsapi/:id\d', 'Api/Post/show',   '', ['method' => 'get']],
        ['api/postsapi/:id\d', 'Api/Post/update', '', ['method' => 'put']],
        ['api/postsapi/:id\d', 'Api/Post/delete', '', ['method' => 'delete']],
    ],
];
