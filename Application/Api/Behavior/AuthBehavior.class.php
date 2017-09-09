<?php
namespace Api\Behavior;

use Api\Controller\BaseController;
use Org\Net\Http;
use Think\Auth;
use Think\Behavior;

/**
 * 检查用户认证的行为。
 */
class AuthBehavior extends Behavior
{
    public function run(&$return)
    {
        $msg = PHP_EOL . 'Api\Behavior\AuthBehavior::run():';

        $return = true;

        $authHeader = Http::getHeaderInfo('Authorization', false);

        $msg .= PHP_EOL . '  $authHeader = ' . $authHeader;

        if ($authHeader) {
            list($jwt) = sscanf($authHeader, 'Authorization: Bearer %s');

            $msg .= PHP_EOL . '  $jwt = ' . $jwt;

            if ($jwt) {
                $User = D('Home/User');
                $user = $User->decodeJwtToken($jwt);

                if ($user !== false) {
                    // authenticated
                    BaseController::$authentication = [
                        'authenticated' => true,
                        'user' => $user,
                    ];
                } else {
                    BaseController::$authentication = [
                        'authenticated' => false,
                        'user' => null,
                    ];
                }
            }
        }

        $msg .= PHP_EOL . '  $user = ' . $user
            . PHP_EOL . '  $return = ' . $return;
        $msg .= PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');
        // trace($msg, '调试', 'DEBUG', true);
    }
}
