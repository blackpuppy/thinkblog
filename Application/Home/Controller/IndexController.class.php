<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $msg = PHP_EOL . 'Home\Controller\IndexController::index():'
            // . PHP_EOL . '  $Think = ' . print_r($Think, true)
            . PHP_EOL . str_repeat('-', 80);
        // \Think\Log::write($msg, 'INFO');

        layout(true);
        $this->display();
    }

    public function add()
    {
        layout(true);
        $this->display();
    }

    public function update()
    {
        layout(true);
        $this->display();
    }
}
