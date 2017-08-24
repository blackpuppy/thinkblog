<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends Controller
{
    /**
     * 注册新用户。
     * @return void
     */
    public function signup()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/users/signup'));
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
                        $this->success(L('SAVE_USER_SUCCESS'), U('/'), 3);
                    } else {
                        $this->error(L('SAVE_USER_FAILURE'), U('/users/signup'), 5);
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
        $this->display();
    }

    /**
     * 注销。
     * @return void
     */
    public function logout()
    {
        $this->display();
    }
}
