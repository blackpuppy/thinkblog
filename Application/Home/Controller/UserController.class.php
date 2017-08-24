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
        $this->display();
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
