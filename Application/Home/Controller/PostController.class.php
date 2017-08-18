<?php
namespace Home\Controller;

use Think\Controller;

class PostController extends Controller
{
    public function index()
    {
        // $msg = PHP_EOL . 'Home\Controller\PostController::index():';

        // try {
            // $Post = D('Post');
            // $posts = $Post->order(['created_at' => 'desc'])->select();

            // $msg .= PHP_EOL . '  $posts = ' . print_r($posts, true)

            // $this->assign('posts', $posts);

            layout(true);
            $this->display();
        // } catch (Exception $e) {
        //     $msg .= PHP_EOL . 'error: ' . $e->getMessage();
        //     throw $e;
        // } finally {
            // $msg .= PHP_EOL . str_repeat('-', 80);
            // \Think\Log::write($msg, 'INFO');
        // }
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
