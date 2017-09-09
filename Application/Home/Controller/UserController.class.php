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
                $User = D('User');

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
                // $username = I('name');
                // $password = I('password');

                $User = D('User');
                $user = $User->create(I('post.'), BaseModel::MODEL_LOGIN);

                $msg .= PHP_EOL . '  post data = ' . print_r(I('post.'), true)
                    . PHP_EOL . '  $user = ' . print_r($user, true);

                if (!$user) {
                    $msg .= PHP_EOL . '  validation error: ' . $User->getError();

                    $data = [
                        'user' => I('post.'),
                        'validationError' => $User->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $user = $User->login($User->name, $User->password);
                    if ($user !== false) {
                        $msg .= PHP_EOL . '  login succeeded!';

                        // set authenticated
                        session('authentication.authenticated', true);
                        session('authentication.user', $user);

                        // redirect to intended page
                        $this->redirect(U(C('AUTH_CONFIG.AUTH_LOGIN_REDIRECT_URL')));
                    } else {
                        $msg .= PHP_EOL . '  login failed';

                        // clear authenticated
                        session('authentication.authenticated', false);
                        session('authentication.user', null);

                        $data = [
                            'user' => I('post.'),
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

        $this->redirect(U('/'));
    }
}
