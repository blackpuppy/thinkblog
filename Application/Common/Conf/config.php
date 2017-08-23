<?php
return [
    //'配置项'=>'配置值'

    // 有些配置必须如此，只写在.env中无效
    'DEFAULT_LANG'      => getenv('DEFAULT_LANG'),      // 默认语言
    'LANG_SWITCH_ON'    => getenv('LANG_SWITCH_ON'),    // 开启语言包功能
    'LANG_AUTO_DETECT'  => getenv('LANG_AUTO_DETECT'),  // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'         => getenv('LANG_LIST'),         // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'      => getenv('VAR_LANGUAGE'),      // 默认语言切换变量

    // URL设置
    'URL_MODEL'         => getenv('URL_MODEL'),         // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
                                                        // 0 (普通模式);
                                                        // 1 (PATHINFO 模式);
                                                        // 2 (REWRITE  模式);
                                                        // 3 (兼容模式)  默认为PATHINFO 模式
    'URL_HTML_SUFFIX'   => getenv('URL_HTML_SUFFIX'),   // URL伪静态后缀设置
    'URL_ROUTER_ON'     => true,  //开启路由
    'URL_ROUTE_RULES'   => [ //定义路由规则
        // '/'             => 'Index/index',

        // '/^posts$/'                 => 'Post/index',
        // '/^posts\/add$/'            => 'Post/add',
        // '/^posts\/update\/(\d+)$/'  => 'Post/update/id/:1',
        // '/^posts\/delete\/(\d+)$/'  => 'Post/delete/id/:1',

        'posts/create'          => 'Post/create',
        'posts/update/:id\d'    => 'Post/update',
        'posts/delete/:id\d'    => 'Post/delete',
        'posts'                 => 'Post/index',
    ],
];
