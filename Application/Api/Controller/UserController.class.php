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
        if (!IS_POST) {
            $this->response(L('METHOD_NOT_ALLOWED'), 'json', 405);
            return;
        }

        $msg = PHP_EOL . 'Api\Controller\UserController::signup():';

        try {
            $input = $this->getPostInput();

            $User = D('Home/User');
            $UserRole = D('Home/UserRole');

            $newUser = $User->create($input);

            $msg .= PHP_EOL . '  $newUser = ' . print_r($newUser, true);

            if (!$newUser) {
                $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                $data = [
                    'user' => $input,
                    'validationError' => $User->getError(),
                ];

                $msg .= PHP_EOL . str_repeat('-', 80);
                \Think\Log::write($msg, 'DEBUG');

                $this->response($data, 'json', 400);
            } else {
                $result = false;

                try {
                    $User->startTrans();

                    $userResult = $User->add();

                    $msg .= PHP_EOL . '  $userResult = ' . print_r($userResult, true);

                    $userRoleInput = [
                        'uid' => $userResult,
                        'group_id' => 2,
                        'created_by' => $userResult,
                    ];
                    $newUserRole = $UserRole->create($userRoleInput);
                    $newUserRole['created_by'] = $userResult;
                    $UserRole->created_by = $userResult;

                    $msg .= PHP_EOL . '  $userRoleInput = ' . print_r($userRoleInput, true)
                        . PHP_EOL . '  $newUserRole = ' . print_r($newUserRole, true);
                        // . PHP_EOL . '  validation error = ' . $UserRole->getError();

                    $roleResult = $UserRole->add();

                    $msg .= PHP_EOL . '  $roleResult = ' . print_r($roleResult, true);

                    $result = $userResult !== false && $roleResult !== false;
                    if ($result) {
                        $User->commit();
                    } else {
                        $User->rollback();
                    }
                } catch (Exception $e) {
                    $User->rollback();
                }

                $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                if ($result !== false) {
                    $newUser['id'] = $result;

                    $newUser = $User->relation(true)->find($userResult);
                    $User->protect($newUser);

                    // generate jwt token
                    $token = $User->generateJwtToken($newUser);

                    $data = [
                        'user' => $newUser,
                    ];
                    $meta = [
                        'message' => L('SIGNUP_USER_SUCCESS'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('token', 'data', 'meta'), 'json', 201);
                } else {
                    $msg .= PHP_EOL . '  signup failed';

                    $meta = [
                        'message' => L('SIGNUP_USER_FAILURE'),
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
}
