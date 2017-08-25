<?php
namespace Home\Behavior;

use Think\Auth;
use Think\Behavior;

/**
 * 检查用户认证与授权的行为。
 */
class AuthBehavior extends Behavior {

    public function run(&$return)
    {
        if (C('AUTH_CONFIG.AUTH_ON')) {
            // 进行权限认证逻辑 如果认证通过 $return = true;
            // 否则用halt输出错误信息
            $ruleName = MODULE_NAME . '/' . ACTION_NAME;
        }
    }
}
