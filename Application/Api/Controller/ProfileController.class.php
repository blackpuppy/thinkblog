<?php
namespace Api\Controller;

use Api\Controller\BaseController;

class ProfileController extends BaseController
{
    /**
     * 查看用户个人资料。
     * @return void
     */
    public function show()
    {
        $msg = PHP_EOL . 'Api\Controller\ProfileController::show():'
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;
        $msg .= PHP_EOL . str_repeat('-', 80);
        \Think\Log::write($msg, 'DEBUG');

        if (!IS_GET) {
            $msg .= PHP_EOL . '  not GET, 405 method not allowed'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
            $this->response(L('METHOD_NOT_ALLOWED'), 'json', 405);
            return;
        }

        if (self::isAuthenticated()) {
            $userId = $this->getCurrentUserId();
        } else {
            $msg .= PHP_EOL . '  not authenticated, 401 unauthorized'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');

            $this->response(L('UNAUTHORIZED'), 'json', 401);
            return;
        }

        $msg = PHP_EOL . '  $userId = ' . $userId;

        try {
            $input = $this->getPostInput();

            $User = D('User');
            $Profile = D('Profile');

            $user = $User->find($userId);
            $profile = $Profile->where(['user_id' => $userId])->find();

            $msg .= PHP_EOL . '  $user = ' . print_r($user, true)
                . PHP_EOL . '  $profile = ' . print_r($profile, true);

            if (!$profile) {
                $profile = $Profile->create();
            }

            $data = compact('user', 'profile');

            $this->response($data, 'json', 400);
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }
}
