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
     * 注册新用户。
     * @return void
     */
    public function signup()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/signup'));
            return;
        }

        $msg = PHP_EOL . 'Api\Controller\UserController::signup():';

        try {
            $User = D('Home/User');

            $newUser = $User->create();

            $msg .= PHP_EOL . '  $newUser = ' . print_r($newUser, true);

            if (!$newUser) {
                $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                $data = [
                    'user' => I('post.'),
                    'validationError' => $User->getError(),
                ];
                $this->assign($data);

                $this->display();
            } else {
                $result = $User->add();

                $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                if ($result !== false) {
                    $newUser['id'] = $result;

                    // set authenticated
                    session('authentication.authenticated', true);
                    session('authentication.user', $newUser);

                    $this->success(L('SIGNUP_USER_SUCCESS'), U('/'), 3);
                } else {
                    $this->error(L('SIGNUP_USER_FAILURE'), U('/signup'), 5);
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

                    $User->protect($user);

                    // generate jwt token
                    $token = $User->generateJwtToken($user);

                    $data = [
                        'user' => $user,
                    ];
                    $meta = [
                        'message' => L('LOGIN_USER_SUCCESS'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('token', 'data', 'meta'), 'json', 200);
                } else {
                    $msg .= PHP_EOL . '  login failed';

                    $meta = [
                        'message' => L('LOGIN_USER_FAILURE'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('meta'), 'json', 401);
                }
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . '  error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            // \Think\Log::write($msg, 'DEBUG');
        }
    }

    /**
     * 注销。
     * @return void
     */
    public function logout()
    {
        // clear authenticated
        session('authentication.authenticated', false);
        session('authentication.user', null);

        $this->redirect(U('/'));
    }
}
