<?php
return array(
    //'配置项'=>'配置值'

    // URL设置
    'URL_ROUTER_ON'   => true,  //开启路由
    'URL_ROUTE_RULES' => array( //定义路由规则
        '/'             => 'Index/index',

        '/posts'        => 'Post/index',
        '/posts/add'    => 'Post/add',
        '/posts/update' => 'Post/update',
    ),
);
