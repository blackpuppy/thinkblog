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
        $return = true;

        $msg = PHP_EOL . 'Home\Behavior\AuthBehavior::run():'
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
            . PHP_EOL . '配置:'
            . PHP_EOL . '  C(AUTH_CONFIG.AUTH_ON) = ' . C('AUTH_CONFIG.AUTH_ON');

        $msg .= PHP_EOL . '  session() = ' . print_r(session(), true);

        $isAuthenticated = session('?authentication.authenticated')
                        && session('authentication.authenticated');

        $url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $loginUrl = U(C('AUTH_CONFIG.AUTH_LOGIN_URL'));
        $loginRedirectUrl = U(C('AUTH_CONFIG.AUTH_LOGIN_REDIRECT_URL'));
        $isPublicUrl = $this->checkPublicUrl($url);

        $msg .= PHP_EOL . '  $isAuthenticated = ' . $isAuthenticated
            . PHP_EOL . '  $url = ' . $url
            . PHP_EOL . '  $loginUrl = ' . $loginUrl
            . PHP_EOL . '  $loginRedirectUrl = ' . $loginRedirectUrl
            . PHP_EOL . '  $isPublicUrl = ' . $isPublicUrl;

        if (!$isPublicUrl) {
            if ($isAuthenticated) {
                $msg .= PHP_EOL . '  member:';

                if (C('AUTH_CONFIG.AUTH_ON')) {
                    $userId = session('authentication.user')['id'];

                    $Auth = new Auth();
                    $isAuthorized = $Auth->check($url, $userId);

                    $msg .= PHP_EOL . '  $userId = ' . $userId
                        . PHP_EOL . '  $isAuthorized = ' . $isAuthorized;

                    if (!$isAuthorized) {
                        $return = false;

                        $msg .= PHP_EOL . '  $return = ' . $return;
                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');

                        // send_http_status(401);

                        // $this->error(L('UNAUTHORIZED'), $loginUrl, 5);
                        // redirect($loginUrl, 5, L('UNAUTHORIZED'));
                        redirect($loginRedirectUrl);
                    }
                }

                // $return = !C('AUTH_CONFIG.AUTH_ON') || $isAuthorized;
            } else {
                $msg .= PHP_EOL . '  public user:';

                if (!$isPublicUrl) {
                    $return = false;

                    $msg .= PHP_EOL . '  $return = ' . $return;
                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    // send_http_status(401);

                    // $this->error(L('UNAUTHORIZED'), $loginUrl, 5);
                    // redirect($loginUrl, 5, L('UNAUTHORIZED'));
                    redirect($loginUrl);
                }

                // $return = !$isAuthenticated && $isPublicUrl;
            }
        }

        $msg .= PHP_EOL . '  $return = ' . $return;
        $msg .= PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');
        // trace($msg, '调试', 'DEBUG', true);
    }

    protected function checkPublicUrl($url)
    {
        $where = array(
            'type'   => 1,
            'status' => 1,
        );
        $rules = M()->table(C('AUTH_CONFIG.AUTH_RULE'))
            ->where($where)->field('name')->select();

        array_walk($rules, function (&$rule)
        {
            $rule = $rule['name'];
        });

        // $msg .= PHP_EOL . '  $rules = ' . print_r($rules, true)

        $isPublicUrl = !in_array($url, $rules);

        return $isPublicUrl;
    }
}
