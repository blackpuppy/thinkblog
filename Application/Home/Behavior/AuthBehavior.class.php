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
        $msg = PHP_EOL . 'Home\Behavior\AuthBehavior::run():';

        $isAuthenticated = session('?authentication.authenticated')
                        && session('authentication.authenticated');

        $url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $loginUrl = U(C('AUTH_CONFIG.AUTH_LOGIN_URL'));

        $msg .= PHP_EOL . '  $isAuthenticated = ' . $isAuthenticated
            . PHP_EOL . '  $url = ' . $url
            . PHP_EOL . '  $loginUrl = ' . $loginUrl;

        if ($isAuthenticated) {
            $msg .= PHP_EOL . '  member:';

            if (C('AUTH_CONFIG.AUTH_ON')) {
                $userId = session('authentication.user')['id'];

                $Auth = new Auth();
                $isAuthorized = $Auth->check($url, $userId);

                if (!$isAuthorized) {
                    // send_http_status(401);

                    // $this->error(L('UNAUTHORIZED'), $loginUrl, 5);
                    // redirect($loginUrl, 5, L('UNAUTHORIZED'));
                    redirect($loginUrl);
                } else {
                    $return = true;
                }
            }
        } else {
            $msg .= PHP_EOL . '  public user:';

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

            $isPublicUrl = !in_array($url, $rules);

            $msg .= PHP_EOL . '  $rules = ' . print_r($rules, true)
                . PHP_EOL . '  $isPublicUrl = ' . $isPublicUrl;

            if (!$isPublicUrl) {
                // send_http_status(401);

                // $this->error(L('UNAUTHORIZED'), $loginUrl, 5);
                // redirect($loginUrl, 5, L('UNAUTHORIZED'));
                redirect($loginUrl);
            } else {
                $return = true;
            }
        }

        $msg .= PHP_EOL . '  $return = ' . $return;
        $msg .= PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');
    }
}
