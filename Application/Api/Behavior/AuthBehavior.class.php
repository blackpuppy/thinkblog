<?php
namespace Api\Behavior;

use Api\Controller\BaseController;
use Firebase\JWT\ExpiredException;
use Org\Net\Http;
use Think\Auth;
use Think\Behavior;

/**
 * 检查用户认证的行为。
 */
class AuthBehavior extends Behavior
{
    public function run(&$params)
    {
        $msg = PHP_EOL . 'Api\Behavior\AuthBehavior::run():';

        $authHeader = Http::getHeaderInfo('Authorization', false);

        $msg .= PHP_EOL . '  $authHeader = ' . $authHeader;

        if ($authHeader) {
            list($jwt) = sscanf($authHeader, 'Authorization: Bearer %s');

            $msg .= PHP_EOL . '  $jwt = ' . $jwt;

            if ($jwt) {
                try {
                    $User = D('Home/User');
                    $user = $User->parseJwtToken($jwt);

                    BaseController::$authentication = [
                        'authenticated' => ($user !== false),
                        'user' => $user ?: null,
                    ];
                } catch (ExpiredException $ee) {
                    // TODO: deal with different exceptions differently
                    $msg .= PHP_EOL . '  ExpiredException: ' .  $ee->getMessage();

                    Http::sendHttpStatus(401);
                    header('Content-Type:application/json; charset=utf-8');
                    $meta = [
                        'message' => L('EXPIRED_TOKEN')
                    ];
                    echo json_encode(compact('meta'), true);
                    exit;
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
