<?php
namespace Home\Behavior;

use Think\Auth;
use Think\Behavior;

/**
 * 调试行为。
 */
class DebugBehavior extends Behavior {

    public function run(&$return)
    {
        $msg = PHP_EOL . 'Home\Behavior\DebugBehavior::run():'
            . PHP_EOL . '系统常量:'
            . PHP_EOL . '  __APP__ = ' . (defined('__APP__') ? __APP__ : '')
            . PHP_EOL . '  __MODULE__ = ' . (defined('__MODULE__') ? __MODULE__ : '')
            . PHP_EOL . '  __CONTROLLER__ = ' . (defined('__CONTROLLER__') ? __CONTROLLER__ : '')
            . PHP_EOL . '  __ACTION__ = ' . (defined('__ACTION__') ? __ACTION__ : '')
            . PHP_EOL . '  __SELF__ = ' . (defined('__SELF__') ? __SELF__ : '')
            . PHP_EOL . '  __INFO__ = ' . __INFO__
            . PHP_EOL . '  MODULE_NAME = ' . MODULE_NAME
            . PHP_EOL . '  CONTROLLER_NAME = ' . (defined('CONTROLLER_NAME') ? CONTROLLER_NAME : '')
            . PHP_EOL . '  ACTION_NAME = ' . (defined('ACTION_NAME') ? ACTION_NAME : '')
            . PHP_EOL . '  REQUEST_METHOD = ' . (defined('REQUEST_METHOD') ? REQUEST_METHOD : '')
            . PHP_EOL . str_repeat('-', 80);
        // Think\Log::write($msg, 'INFO');
        trace($msg, '调试', 'DEBUG', true);


        $return = true;
    }
}
