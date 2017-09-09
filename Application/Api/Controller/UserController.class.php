<?php
namespace Api\Controller;

use Home\Model\BaseModel;
use Api\Controller\BaseController;

/**
 * 用户API控制器。
 */
class UserController extends BaseController
{
    /**
     * 登录。
     * @return void
     */
    public function login()
    {
        if (!IS_POST) {
            $this->response(L('METHOD_NOT_ALLOWED'), 'json', 405);
            return;
        }

        $msg = PHP_EOL . 'Api\Controller\UserController::login():';

        try {
            $input = $this->getPostInput();

            $User = D('Home/User');
            $user = $User->create($input, BaseModel::MODEL_LOGIN);

            $msg .= PHP_EOL . '  post data = ' . print_r($input, true)
                . PHP_EOL . '  $user = ' . print_r($user, true);

            if (!$user) {
                $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                $data = [
                    'data' => $input,
                    'validationError' => $User->getError(),
                ];

                $msg .= PHP_EOL . str_repeat('-', 80);
                \Think\Log::write($msg, 'DEBUG');

                $this->response($data, 'json', 400);
            } else {
                $user = $User->login($User->name, $User->password);
                if ($user !== false) {
                    $msg .= PHP_EOL . '  login succeeded!';

                    // TODO: protect password
                    // TODO: generate jwt

                    $data = [
                        'user' => $user,
                    ];
                    $meta = [
                        'message' => L('LOGIN_USER_SUCCESS'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('data', 'meta'), 'json', 201);
                } else {
                    $msg .= PHP_EOL . '  login failed';

                    $meta = [
                        'message' => L('LOGIN_USER_FAILURE'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('meta'), 'json', 500);
                }
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . '  error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }
}
