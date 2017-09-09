<?php
namespace Api\Controller;

use Think\Controller\RestController;

/**
 * API控制器基类。
 */
class BaseController extends RestController
{
    public static $authentication = [
        'authenticated' => false,
        'user' => null,
    ];

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
}
