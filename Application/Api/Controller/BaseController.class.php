<?php
namespace Api\Controller;

use Think\Controller\RestController;

/**
 * API控制器基类。
 */
class BaseController extends RestController
{
    /**
     * 构造函数
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 读取POST输入。
     * @return array POST输入。
     */
    public function getPostInput()
    {
        return json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
    }

    protected static $authentication = [
        'authenticated' => false,
        'user' => null,
    ];

    /**
     * 设置用户认证。
     * @param array $auth 用户认证数据
     * @return void
     */
    public static function setAuthentication($auth)
    {
        if (isset($auth['authenticated'])) {
            self::$authentication['authenticated'] = $auth['authenticated'];
        }
        if (isset($auth['user'])) {
            self::$authentication['user'] = $auth['user'];
        }

        // $msg .= PHP_EOL . 'Api\Controller\BaseController::setAuthentication():'
        //     . PHP_EOL . '  $auth = ' . print_r($auth, true)
        //     . PHP_EOL . '  self::$authentication = ' . print_r(self::$authentication, true)
        //     . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');
    }

    /**
     * 判断是否通过用户认证。
     * @return boolean 是否通过用户认证。
     */
    public static function isAuthenticated()
    {
        // $msg .= PHP_EOL . 'Api\Controller\BaseController::isAuthenticated():'
        //     . PHP_EOL . '  return ' . self::$authentication['authenticated']
        //     . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return self::$authentication['authenticated'];
    }

    /**
     * 获取给通过用户认证的用户。
     * @return array 通过用户认证的用户，如未登录则返回 null。
     */
    public static function getCurrentUser()
    {
        $user = self::isAuthenticated() ? self::$authentication['user'] : null;

        // $msg .= PHP_EOL . 'Api\Controller\BaseController::getCurrentUser():'
        //     . PHP_EOL . '  return ' . print_r($user, true)
        //     . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $user;
    }

    /**
     * 获取给通过用户认证的用户 id。
     * @return array 通过用户认证的用户 id，如未登录则返回 0。
     */
    public static function getCurrentUserId()
    {
        $currentUserId = self::isAuthenticated() ? self::getCurrentUser()['id'] : 0;

        // $msg .= PHP_EOL . 'Api\Controller\BaseController::getCurrentUserId():'
        //     . PHP_EOL . '  return ' . $currentUserId
        //     . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'DEBUG');

        return $currentUserId;
    }
}
