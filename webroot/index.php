<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
// phpinfo();
// exit(0);

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    die('require PHP > 5.3.0 !');
}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 是否生成目录安全文件
define('BUILD_DIR_SECURE', false);

// 定义应用目录
define('APP_PATH', './Application/');

// 引入ThinkPHP入口文件
require './vendor/topthink/thinkphp/ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单

$msg = PHP_EOL . 'index.php:'
    . PHP_EOL . '路径常量:'
    . PHP_EOL . '  THINK_VERSION = ' . THINK_VERSION
    . PHP_EOL . '  THINK_PATH = ' . THINK_PATH
    . PHP_EOL . '  LIB_PATH = ' . LIB_PATH
    . PHP_EOL . '  CORE_PATH = ' . CORE_PATH
    . PHP_EOL . '  APP_PATH = ' . APP_PATH
    . PHP_EOL . '  LOG_PATH = ' . LOG_PATH
    . PHP_EOL . '  DATA_PATH = ' . DATA_PATH
    . PHP_EOL . '  APP_DEBUG = ' . APP_DEBUG
    . PHP_EOL . '系统常量:'
    . PHP_EOL . '  __APP__ = ' . __APP__
    . PHP_EOL . '  __MODULE__ = ' . __MODULE__
    . PHP_EOL . '  __CONTROLLER__ = ' . __CONTROLLER__
    . PHP_EOL . '  __ACTION__ = ' . __ACTION__
    . PHP_EOL . '  __SELF__ = ' . __SELF__
    . PHP_EOL . '  __INFO__ = ' . __INFO__
    . PHP_EOL . '  MODULE_NAME = ' . MODULE_NAME
    . PHP_EOL . '  CONTROLLER_NAME = ' . CONTROLLER_NAME
    . PHP_EOL . '  ACTION_NAME = ' . ACTION_NAME
    . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD
    . PHP_EOL . '  IS_AJAX = ' . IS_AJAX
    . PHP_EOL . '配置:'
    . PHP_EOL . '  C(URL_MODEL) = ' . C('URL_MODEL')
    . PHP_EOL . str_repeat('-', 80);
Think\Log::write($msg, 'INFO');
