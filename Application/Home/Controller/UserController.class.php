<?php
namespace Home\Controller;

use Carbon\Carbon;
use Home\Model\BaseModel;
use Think\Controller;

/**
 * 用户控制器。
 */
class UserController extends Controller
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

        $msg = PHP_EOL . 'Home\Controller\UserController::signup():';

        $this->assign('title', L('SIGN_UP'));

        if (IS_GET) {
            $this->display();
        } elseif (IS_POST) {
            try {
                $input = I('post.');

                $User = D('User');
                $newUser = $User->create($input);

                $msg .= PHP_EOL . '  $newUser = ' . print_r($newUser, true);

                if (!$newUser) {
                    $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                    $User->protect($input);
                    $data = [
                        'user' => $input,
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
    }

    /**
     * 登录。
     * @return void
     */
    public function login()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/login'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\UserController::login():';

        $this->assign('title', L('LOGIN'));

        if (IS_GET) {
            $this->display();
        } elseif (IS_POST) {
            try {
                $input = I('post.');

                $User = D('User');
                $user = $User->create($input, BaseModel::MODEL_LOGIN);

                $msg .= PHP_EOL . '  post data = ' . print_r($input, true)
                    . PHP_EOL . '  $user = ' . print_r($user, true);

                if (!$user) {
                    $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                    $data = [
                        'user' => $input,
                        'validationError' => $User->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $user = $User->login($User->name, $User->password);
                    if ($user !== false) {
                        $msg .= PHP_EOL . '  login succeeded!';
                        $msg .= PHP_EOL . '  remember me = ' . $input['remember'];

                        // set authenticated
                        session('authentication.authenticated', true);
                        session('authentication.user', $user);

                        if ($input['remember']) {
                            $token = bin2hex(openssl_random_pseudo_bytes(32));
                            $expiredAt = strtotime(C('REMEMBER_ME_TIMEOUT', null, '1 day'));

                            // $msg .= PHP_EOL . '  remember me token = ' . $token;
                            // $msg .= PHP_EOL . '  remember me expiredAt = ' . $expiredAt;

                            $result = $User->saveRememberMe($user['id'], $token, $expiredAt);

                            // $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                            if ($result) {
                                setcookie(C('REMEMBER_ME_COOKIE_ID'), $token, $expiredAt);

                                $msg .= PHP_EOL . '  remember me cookie set!';
                            }
                        }

                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');

                        // redirect to intended page
                        $this->redirect(U(C('AUTH_CONFIG.AUTH_LOGIN_REDIRECT_URL')));
                    } else {
                        $msg .= PHP_EOL . '  login failed';

                        // clear authenticated
                        session('authentication.authenticated', false);
                        session('authentication.user', null);

                        $data = [
                            'user' => $input,
                            'validationError' => L('LOGIN_USER_FAILURE'),
                        ];
                        $this->assign($data);

                        $this->display();
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

    /**
     * 注销。
     * @return void
     */
    public function logout()
    {
        // clear authenticated
        session('authentication.authenticated', false);
        session('authentication.user', null);

        setcookie(C('REMEMBER_ME_COOKIE_ID'), null);

        $this->redirect(U('/'));
    }

    /**
     * 生成验证码。
     * @return void
     */
    public function recaptcha()
    {
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

    /* 校验验证码 */
    public function checkRecaptcha($code, $id = '')
    {
        $verify = new \Think\Verify([
            'reset' => false,
        ]);
        $valid = $verify->check($code, $id);

        $msg = PHP_EOL . 'Home\Controller\UserController::checkRecaptcha():'
            . PHP_EOL . '  $code = ' . $code
            . PHP_EOL . '  $id = ' . $id
            . PHP_EOL . '  $valid = ' . $valid
            . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        $this->ajaxReturn($valid, 'json');
    }

    /**
     * 忘记密码。
     * @return void
     */
    public function forget_password()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/login'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\UserController::forget_password():';

        $this->assign('title', L('FORGET_PASSWORD'));

        if (IS_GET) {
            $this->display();
        } elseif (IS_POST) {
            try {
                $User = D('User');
                $PasswordReset = D('PasswordReset');

                $input = I('post.');

                // $msg .= PHP_EOL . '  POST data = ' . print_r($input, true);

                $user = $User->relation(true)->where(['name' => $input['name']])->find();
                if (!$user) {
                    $msg .= PHP_EOL . '  user not found';

                    $data = [
                        'passwordReset' => $input,
                        'validationError' => L('USER_NOT_FOUND'),
                    ];
                    $this->assign($data);

                    $this->display();
                }

                $msg .= PHP_EOL . '  user = ' . print_r($user, true);

                $input['user_id'] = $user['id'];
                $input['token'] =
                    bin2hex(openssl_random_pseudo_bytes(32));
                $expiredAt = new Carbon(C('RESET_TOKEN_TIMEOUT', null, '1 day'));
                $input['token_expired_at'] =
                    $expiredAt->toDateTimeString('Y-m-d H:i:s');
                $input['created_by'] = $user['id'];

                $msg .= PHP_EOL . '  $input = ' . print_r($input, true);

                $reset = $PasswordReset->create($input);

                $msg .= PHP_EOL . '  $reset = ' . print_r($reset, true);

                if (!$reset) {
                    $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                    $data = [
                        'passwordReset' => $input,
                        'validationError' => $User->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $PasswordReset->created_by = $user['id'];

                    $passwordReset = $PasswordReset->data();
                    $msg .= PHP_EOL . '  $passwordReset = ' . print_r($passwordReset, true);

                    $result = $PasswordReset->add();

                    // $msg .= PHP_EOL . '  $sql = ' . $sql;
                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                    if ($result !== false) {
                        $this->sendResetPasswordEmail($passwordReset, $user);

                        $this->success(L('FORGET_PASSWORD_SUCCESS'), U('/posts'), 3);
                    } else {
                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');

                        $this->error(L('FORGET_PASSWORD_FAILURE'), U('/forget_password'), 5);
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

    protected function sendResetPasswordEmail($passwordReset, $user)
    {
        $to = [$passwordReset['email'] => $passwordReset['name']];
        $subject = 'Forget Password';

        $data = [
            'passwordReset' => $passwordReset,
            'user' => $user,
        ];
        $this->assign($data);
        layout(false);
        $body = $this->fetch('Email:forget_password');

        return sendMail($to, $subject, $body);
    }

    /**
     * 重置密码。
     * @return void
     */
    public function reset_password()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/login'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\UserController::reset_password():';

        $this->assign('title', L('RESET_PASSWORD'));

        if (IS_GET) {
            $token = I('get.token');

            $msg .= PHP_EOL . '  $token = ' . $token;

            $PasswordReset = D('PasswordReset');

            $reset = $PasswordReset->where(['token' => $token])->find();

            $msg .= PHP_EOL . '  $reset = ' . print_r($reset, true);

            $valid = false;

            if ($reset) {
                $now = Carbon::now();
                $expiredAt = Carbon::createFromFormat('Y-m-d H:i:s', $reset['token_expired_at']);

                $msg .= PHP_EOL . '  $now = ' . $now;
                $msg .= PHP_EOL . '  $expiredAt = ' . $expiredAt;

                if ($now->lte($expiredAt)) {
                    $valid = true;

                    $msg .= PHP_EOL . '  $token is valid and not expired';
                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $data = [
                        'passwordReset' => $reset,
                    ];
                    $this->assign($data);

                    $this->display();
                }
            }

            if (!$valid) {
                $msg .= PHP_EOL . '  $token is invalid';
                $msg .= PHP_EOL . str_repeat('-', 80);
                \Think\Log::write($msg, 'DEBUG');

                $this->error(L('RESET_TOKEN_INVALID'), U('/login'), 5);
            }
        } elseif (IS_POST) {
            try {
                $User = D('User');
                $PasswordReset = D('PasswordReset');

                $input = I('post.');

                $msg .= PHP_EOL . '  POST data = ' . print_r($input, true);

                $user = $User->relation(true)->where(['name' => $input['name']])->find();
                if (!$user) {
                    $msg .= PHP_EOL . '  user not found';

                    $data = [
                        'passwordReset' => $input,
                        'validationError' => L('USER_NOT_FOUND'),
                    ];
                    $this->assign($data);

                    $this->display();
                }

                $msg .= PHP_EOL . '  user = ' . print_r($user, true);

                $input['user_id'] = $user['id'];
                $input['token'] =
                    bin2hex(openssl_random_pseudo_bytes(32));
                $expiredAt = new Carbon(C('RESET_TOKEN_TIMEOUT', null, '1 day'));
                $input['token_expired_at'] =
                    $expiredAt->toDateTimeString('Y-m-d H:i:s');
                $input['created_by'] = $user['id'];

                $msg .= PHP_EOL . '  $input = ' . print_r($input, true);

                $reset = $PasswordReset->create($input);

                $msg .= PHP_EOL . '  $reset = ' . print_r($reset, true);

                if (!$reset) {
                    $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                    $data = [
                        'passwordReset' => $input,
                        'validationError' => $User->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $PasswordReset->created_by = $user['id'];

                    $passwordReset = $PasswordReset->data();
                    $msg .= PHP_EOL . '  $passwordReset = ' . print_r($passwordReset, true);

                    $result = $PasswordReset->add();

                    // $msg .= PHP_EOL . '  $sql = ' . $sql;
                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                    if ($result !== false) {
                        $this->sendResetPasswordEmail($passwordReset, $user);

                        $this->success(L('FORGET_PASSWORD_SUCCESS'), U('/posts'), 3);
                    } else {
                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');

                        $this->error(L('FORGET_PASSWORD_FAILURE'), U('/forget_password'), 5);
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
}
