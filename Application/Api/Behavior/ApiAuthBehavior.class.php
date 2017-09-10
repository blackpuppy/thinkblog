<?php
namespace Api\Behavior;

use Api\Controller\BaseController;
use Firebase\JWT\ExpiredException;
use Home\Behavior\AuthBehavior;
use Org\Net\Http;
use Think\Auth;
// use Think\Behavior;

/**
 * 检查用户认证的行为。
 */
class ApiAuthBehavior extends AuthBehavior
{
    public function run(&$params)
    {
        $msg = PHP_EOL . 'Api\Behavior\ApiAuthBehavior::run():';

        // 1. 用户认证
        $authHeader = Http::getHeaderInfo('Authorization', false);

        $msg .= PHP_EOL . '  $authHeader = ' . $authHeader;

        if ($authHeader) {
            list($jwt) = sscanf($authHeader, 'Authorization: Bearer %s');

            $msg .= PHP_EOL . '  $jwt = ' . $jwt;

            if ($jwt) {
                try {
                    $User = D('Home/User');
                    $user = $User->parseJwtToken($jwt);

                    $msg .= PHP_EOL . '  $user = ' . print_r($user, true);

                    BaseController::$authentication = [
                        'authenticated' => ($user !== false),
                        'user' => $user ?: null,
                    ];

                } catch (ExpiredException $ee) {
                    // TODO: deal with different exceptions differently
                    $msg .= PHP_EOL . '  ExpiredException: ' .  $ee->getMessage();

                    $this->sendJsonResponse(401, L('EXPIRED_TOKEN'));
                    exit;
                }
            }
        }

        // 2. 用户授权
        $isAuthenticated = BaseController::$authentication['authenticated'];
        $url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
        $isPublicUrl = $this->checkPublicUrl($url);

        $msg .= PHP_EOL . '  $isAuthenticated = ' . $isAuthenticated
            . PHP_EOL . '  $url = ' . $url
            . PHP_EOL . '  $isPublicUrl = ' . $isPublicUrl;

        if (!$isPublicUrl) {
            if ($isAuthenticated) {
                $msg .= PHP_EOL . '  member:';

                if (C('AUTH_CONFIG.AUTH_ON', null, false)) {
                    $userId = BaseController::$authentication['authenticated']['id'];

                    $Auth = new Auth();
                    $isAuthorized = $Auth->check($url, $userId);

                    $msg .= PHP_EOL . '  $userId = ' . $userId
                        . PHP_EOL . '  $isAuthorized = ' . $isAuthorized;

                    if (!$isAuthorized) {
                        $msg .= PHP_EOL . '  not authorized';
                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');
                        // trace($msg, '调试', 'DEBUG', true);

                        $this->sendJsonResponse(401, L('UNAUTHORIZED'));
                        exit;
                    }
                }

                // $return = !C('AUTH_CONFIG.AUTH_ON', null, false) || $isAuthorized;
            } else {
                $msg .= PHP_EOL . '  public user:';

                if (!$isPublicUrl) {
                    $msg .= PHP_EOL . '  not authorized';
                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');
                    // trace($msg, '调试', 'DEBUG', true);

                    $this->sendJsonResponse(401, L('UNAUTHORIZED'));
                    exit;
                }
            }
        }

        $msg .= PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');
        // trace($msg, '调试', 'DEBUG', true);
    }

    /**
     * 发送JSON响应。
     * @param integer $code    状态码
     * @param string  $message 信息
     * @return void
     */
    protected function sendJsonResponse($code, $message)
    {
        // send_http_status($code);
        Http::sendHttpStatus($code);
        header('Content-Type:application/json; charset=utf-8');
        $meta = compact('message');
        echo json_encode(compact('meta'), true);
    }
}
