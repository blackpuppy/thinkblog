<?php
namespace Api\Controller;

use Api\Controller\BaseController;

class ProfileController extends BaseController
{
    /**
     * 读取用户个人资料。
     * @return void
     */
    public function show()
    {
        $msg = PHP_EOL . 'Api\Controller\ProfileController::show():'
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

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

        $msg .= PHP_EOL . '  $userId = ' . $userId;

        try {
            $input = $this->getPostInput();

            $User = D('Home/User');

            $user = $User->relation('profile')->find($userId);

            $msg .= PHP_EOL . '  $user = ' . print_r($user, true);

            $data = compact('user');
            $User->protect($data);

            $msg .= PHP_EOL . '  $data = ' . print_r($data, true);
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');

            $this->response($data, 'json', 400);
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'DEBUG');
        }
    }

    /**
     * 保存用户个人资料。
     * @return void
     */
    public function store()
    {
        $msg = PHP_EOL . 'Api\Controller\ProfileController::store():'
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        if (!IS_POST) {
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

        $msg .= PHP_EOL . '  $userId = ' . $userId;

        try {
            $input = $this->getPostInput();

            $msg .= PHP_EOL . '  $input = ' . print_r($input, true);

            $User = D('Home/User');
            $Profile = D('Home/Profile');

            $userInput = $input['user'];
            if (empty($userInput['password']) && empty($userInput['confirm_password'])) {
                unset($userInput['password']);
            }
            $user = $User->create($userInput);
            $User->updated_by = $user['updated_by'] = $userId;

            $profileInput = $input['user']['profile'];
            if (!$profileInput['id']) {
                unset($profileInput['id']);
            }
            $profile = $Profile->create($profileInput);
            $Profile->user_id = $profile['user_id'] = $userId;
            if ($profile['id']) {
                $Profile->updated_by = $profile['updated_by'] = $userId;
            } else {
                $Profile->created_by = $profile['created_by'] = $userId;
            }

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

                $msg .= PHP_EOL . '  $data = ' . print_r($data, true);
                $msg .= PHP_EOL . str_repeat('-', 80);
                \Think\Log::write($msg, 'DEBUG');

                $this->response($data, 'json', 400);
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

                        if ($profileResult !== false) {
                            $profile['id'] = $profileResult;
                        }
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
                    $msg .= PHP_EOL . '  saving profile succeeded';

                    $data = compact('user', 'profile');
                    $meta = [
                        'message' => L('SAVE_PROFILE_SUCCESS'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('data', 'meta'), 'json', 200);
                } else {
                    $msg .= PHP_EOL . '  saving profile failed';

                    $meta = [
                        'message' => L('SAVE_PROFILE_FAILURE'),
                    ];

                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'DEBUG');

                    $this->response(compact('meta'), 'json', 500);
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
