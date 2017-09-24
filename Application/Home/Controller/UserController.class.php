<?php
namespace Home\Controller;

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
        \Think\Log::write($msg, 'DEBUG');

        $this->ajaxReturn($valid, 'json');
    }
}
