<?php
$isAzure = false;
$dbHost = "";
$dbUser = "";
$dbPwd = "";

// 解析 Azure MySQL in App 数据库连接字符串
foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
        continue;
    }

    $isAzure = true;
    $dbHost = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    // $dbName = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $dbUser = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $dbPwd = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
}

if (!$isAzure) {
    $dbHost = getenv('DB_HOST');
    $dbUser = getenv('DB_NAME');
    $dbPwd = getenv('DB_PWD');
}

return [
    //'配置项'=>'配置值'

    // 有些配置必须这样引入配置文件，只写在.env中无效
    'DEFAULT_LANG'      => getenv('DEFAULT_LANG'),      // 默认语言
    'DEFAULT_TIMEZONE'  => getenv('DEFAULT_TIMEZONE'),  // 默认时区
    'LANG_SWITCH_ON'    => getenv('LANG_SWITCH_ON'),    // 开启语言包功能
    'LANG_AUTO_DETECT'  => getenv('LANG_AUTO_DETECT'),  // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'         => getenv('LANG_LIST'),         // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'      => getenv('VAR_LANGUAGE'),      // 默认语言切换变量

    'DB_TYPE'       => getenv('DB_TYPE'),       // 数据库类型
    'DB_HOST'       => $dbHost,                 // 服务器地址
    'DB_NAME'       => getenv('DB_NAME'),       // 数据库名
    'DB_USER'       => $dbUser,                 // 用户名
    'DB_PWD'        => $dbPwd,                  // 密码
    'DB_PORT'       => getenv('DB_PORT'),       // 端口
    'DB_PREFIX'     => getenv('DB_PREFIX'),     // 数据库表前缀
    'DB_CHARSET'    => getenv('DB_CHARSET'),    // 字符集
    'DB_DEBUG'      => getenv('DB_DEBUG'),      // 数据库调试模式 开启后可以记录SQL日志
    'DB_ID_QUOTE'   => getenv('DB_ID_QUOTE'),   // 数据库标识符引号，MySQL为`，PostgreSQL为"

    // URL设置
    'URL_MODEL'         => getenv('URL_MODEL'),         // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
                                                        // 0 (普通模式);
                                                        // 1 (PATHINFO 模式);
                                                        // 2 (REWRITE  模式);
                                                        // 3 (兼容模式)  默认为PATHINFO 模式
    'URL_HTML_SUFFIX'   => getenv('URL_HTML_SUFFIX'),   // URL伪静态后缀设置
    'URL_ROUTER_ON'     => true,  //开启路由
    'URL_ROUTE_RULES'   => [ //定义路由规则
        // '/'             => 'Home/Index/index',

        'users/signup'          => 'Home/User/signup',
        'users/login'           => 'Home/User/login',
        'users/logout'          => 'Home/User/logout',
        'users/profile'         => 'Home/Profile/edit',

        // '/^posts$/'                 => 'Home/Post/index',
        // '/^posts\/add$/'            => 'Home/Post/add',
        // '/^posts\/update\/(\d+)$/'  => 'Home/Post/update/id/:1',
        // '/^posts\/delete\/(\d+)$/'  => 'Home/Post/delete/id/:1',

        'posts/create'          => 'Home/Post/create',
        'posts/update/:id\d'    => 'Home/Post/update',
        'posts/delete/:id\d'    => 'Home/Post/delete',
        'posts'                 => 'Home/Post/index',
    ],

    // 日志
    'LOG_EXCEPTION_RECORD'  => getenv('LOG_EXCEPTION_RECORD'),  // 是否记录异常信息日志
    'SHOW_PAGE_TRACE'       => getenv('SHOW_PAGE_TRACE'),       // 是否显示页面Trace信息
    'PAGE_TRACE_SAVE'       => getenv('PAGE_TRACE_SAVE'),       // 是否保存页面Trace信息到日志目录中

    'SESSION_OPTIONS' => [
        'name'      => getenv('SESSION_OPTIONS_NAME'),          // session_name 值
        'expire'    => getenv('SESSION_OPTIONS_EXPIRE'),        // session.gc_maxlifetime 设置值
    ],

    'AUTH_CONFIG' => [
        'AUTH_ON' => getenv('AUTH_ON'),                                 // 认证开关
        'AUTH_TYPE' => getenv('AUTH_TYPE'),                             // 认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP' =>
            getenv('DB_PREFIX') . getenv('AUTH_GROUP'),                 // 用户组数据表名
        'AUTH_GROUP_ACCESS' =>
            getenv('DB_PREFIX') . getenv('AUTH_GROUP_ACCESS'),          // 用户-用户组关系表
        'AUTH_RULE' =>
            getenv('DB_PREFIX') . getenv('AUTH_RULE'),                  // 权限规则表
        'AUTH_USER' =>
            getenv('DB_PREFIX') . getenv('AUTH_USER'),                  // 用户信息表
        'AUTH_LOGIN_URL' => getenv('AUTH_LOGIN_URL'),                   // 登录页面地址
        'AUTH_LOGIN_REDIRECT_URL' => getenv('AUTH_LOGIN_REDIRECT_URL'), // 登录成功后跳转地址
    ],
];
