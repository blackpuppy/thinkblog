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
    public function show()
    {
        $msg = PHP_EOL . 'Home\Controller\ProfileController::view():'
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        if (!IS_GET) {
            $msg .= PHP_EOL . '  not GET, return to homepage'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
            $this->redirect(U('/'));
            return;
        }

        $userId = getCurrentUserId();
        if (!$userId) {
            $msg .= PHP_EOL . '  no $userId, return to homepage'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');

            $this->redirect(U('/'));
            return;
        }

        $msg = PHP_EOL . '  $userId = ' . $userId;

        $this->assign('title', L('EDIT_PROFILE'));

        try {
            $User = D('User');
            $Profile = D('Profile');

            $user = $User->find($userId);
            $profile = $Profile->where(['user_id' => $userId])->find();

            $msg .= PHP_EOL . '  $user = ' . print_r($user, true)
                . PHP_EOL . '  $profile = ' . print_r($profile, true);

            if (!$profile) {
                $profile = $Profile->create();
            }

            $this->assign(compact('user', 'profile'));
            $this->display();
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            // \Think\Log::write($msg, 'DEBUG');
        }
    }

    /**
     * 修改用户个人资料。
     * @return void
     */
    public function edit()
    {
        $msg = PHP_EOL . 'Home\Controller\ProfileController::edit():'
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/'));
            return;
        }

        $userId = getCurrentUserId();
        if (!$userId) {
            $msg .= PHP_EOL . '  no $userId, return to homepage'
                . PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');

            $this->redirect(U('/'));
            return;
        }

        $msg .= PHP_EOL . '  $userId = ' . $userId;

        $this->assign('title', L('EDIT_PROFILE'));

        try {
            $User = D('User');
            $Profile = D('Profile');

            if (IS_GET) {
                $user = $User->find($userId);
                $profile = $Profile->where(['user_id' => $userId])->find();

                $msg .= PHP_EOL . '  $user = ' . print_r($user, true)
                    . PHP_EOL . '  $profile = ' . print_r($profile, true);

                if (!$profile) {
                    $profile = $Profile->create();
                }

                $this->assign(compact('user', 'profile'));
                $this->display();
            } elseif (IS_POST) {
                $userInput = I('post.user');
                if (empty($userInput['password']) && empty($userInput['confirm_password'])) {
                    unset($userInput['password']);
                }
                $user = $User->create($userInput);

                $profileInput = I('post.profile');
                if (!$profileInput['id']) {
                    unset($profileInput['id']);
                }
                $profile = $Profile->create($profileInput);

                // $msg .= PHP_EOL . '  $userInput = ' . print_r($userInput, true)
                //     . PHP_EOL . '  $profileInput = ' . print_r($profileInput, true);
                $msg .= PHP_EOL . '  $user = ' . print_r($user, true)
                    . PHP_EOL . '  $profile = ' . print_r($profile, true);

                if (!$user || !$profile) {
                    $msg .= PHP_EOL . '  user validation error: ' . $User->getError()
                        . PHP_EOL . '  profile validation error: ' . $Profile->getError();

                    $User->protect($userInput);

                    $data = [
                        'user' => $userInput,
                        'profile' => $profileInput,
                        'userValidationError' => $User->getError(),
                        'profileValidationError' => $Profile->getError(),
                    ];
                    $this->assign($data);

                    $msg .= PHP_EOL . '  $data = ' . print_r($data, true);

                    $this->display();
                } else {
                    $msg .= PHP_EOL . '  validation passed';

                    $result = false;

                    try {
                        $User->startTrans();

                        $userResult = $User->save();

                        if (isset($profile['id'])) {
                            $msg .= PHP_EOL . '  going to save profile';
                            $profileResult = $Profile->save();
                        } else {
                            $msg .= PHP_EOL . '  going to add profile';
                            $profileResult = $Profile->add();
                        }

                        $msg .= PHP_EOL . '  $userResult = ' . print_r($userResult, true)
                            . PHP_EOL . '  $profileResult = ' . print_r($profileResult, true);

                        $result = $userResult !== false && $profileResult !== false;
                        if ($result) {
                            $User->commit();
                        } else {
                            $User->rollback();
                        }
                    } catch (Exception $e) {
                        $User->rollback();
                    }

                    if ($result) {
                        $this->success(L('SAVE_PROFILE_SUCCESS'), U('/posts'), 3);
                    } else {
                        $msg .= PHP_EOL . str_repeat('-', 80);
                        \Think\Log::write($msg, 'DEBUG');

                        $this->error(L('SAVE_PROFILE_FAILURE'), U('/profile/edit'), 5);
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
