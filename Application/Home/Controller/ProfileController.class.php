<?php
namespace Home\Controller;

use Think\Controller;
use Think\Model;

class ProfileController extends Controller
{
    /**
     * 查看用户个人资料。
     * @return void
     */
    public function view()
    {
        if (!IS_GET) {
            $this->redirect(U('/'));
            return;
        }

        $Profile = D('Profile');
        $userId = getCurrentUserId();
        if (!$userId) {
            $this->redirect(U('/'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\ProfileController::view():'
            . PHP_EOL . '  $userId = ' . $userId
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        $this->assign('title', L('EDIT_PROFILE'));

        try {
            $profile = $Profile->where(['user_id' => $userId])->find();

            $msg .= PHP_EOL . '  $profile = ' . print_r($profile, true);

            if (!$profile) {
                $profile = $Profile->create();
            }

            $this->assign('profile', $profile);
            $this->display();
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }

    /**
     * 修改用户个人资料。
     * @return void
     */
    public function edit()
    {
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/'));
            return;
        }

        $Profile = D('Profile');
        $userId = getCurrentUserId();
        if (!$userId) {
            $this->redirect(U('/'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\ProfileController::edit():'
            . PHP_EOL . '  $userId = ' . $userId
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        $this->assign('title', L('EDIT_PROFILE'));

        try {
            if (IS_GET) {
                $profile = $Profile->where(['user_id' => $userId])->find();

                $msg .= PHP_EOL . '  $profile = ' . print_r($profile, true);

                if (!$profile) {
                    $profile = $Profile->create();
                }

                $this->assign('profile', $profile);
                $this->display();
            } elseif (IS_POST) {
                $profile = $Profile->create();

                $msg .= PHP_EOL . '  $profile = ' . print_r($profile, true);

                if (!$profile) {
                    $msg .= PHP_EOL . '  validation error: ' . $Profile->getError();

                    $data = [
                        'profile' => I('post.'),
                        'validationError' => $Profile->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $msg .= PHP_EOL . '  validation passed';

                    $result = $profile['id'] ? $Profile->save() : $Profile->add();

                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                    if ($result !== false) {
                        $this->success(L('SAVE_PROFILE_SUCCESS'), U('/'), 3);
                    } else {
                        $this->error(L('SAVE_PROFILE_FAILURE'), U('/'), 5);
                    }
                }
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }
}
