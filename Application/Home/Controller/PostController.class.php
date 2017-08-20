<?php
namespace Home\Controller;

use Think\Controller;

class PostController extends Controller
{
    /**
     * 文章列表。
     * @return void
     */
    public function index()
    {
        $msg = PHP_EOL . 'Home\Controller\PostController::index():';

        try {
            $Post = D('Post');
            $posts = $Post->order(['id' => 'desc'])->select();

            $msg .= PHP_EOL . '  $posts = ' . print_r($posts, true);

            $this->assign('posts', $posts);

            $this->display();
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            // \Think\Log::write($msg, 'INFO');
        }
    }

    /**
     * 文章列表。
     * @return void
     */
    public function create()
    {
        $this->display();
    }

    public function add()
    {
        if (!IS_POST) {
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::add():';

        try {
            $Post = D('Post');
            $Post->create();
            $Post->title = I('title');
            $Post->content = I('content');
            $Post->created_at = date('Y-m-d H:i:s.u');
            $result = $Post->add();

            $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

            if ($result !== false) {
                $this->success('文章保存成功！', U('/posts'), 3);
            } else {
                $this->error('文章保存失败！', U('/posts'), 5);
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }

    public function edit($id)
    {
        if (!IS_GET) {
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::edit():'
            . PHP_EOL . '  $id = ' . $id;

        try {
            $Post = D('Post');
            $post = $Post->find($id);

            $msg .= PHP_EOL . '  $post = ' . print_r($post, true);

            if (!$post) {
                $msg .= PHP_EOL . '  文章不存在！';

                $this->error('文章不存在！', U('/posts'), 5);
            }

            $this->assign('post', $post);

            $this->display();
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }

    public function update($id)
    {
        if (!IS_POST) {
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::update():'
            . PHP_EOL . '  $id = ' . $id;

        try {
            $Post = D('Post');
            $post = $Post->find($id);

            $msg .= PHP_EOL . '  $post = ' . print_r($post, true);

            if (!$post) {
                $msg .= PHP_EOL . '  文章不存在！';

                $this->error('文章不存在！', U('/posts'), 5);
            }

            // if ($post['id'] !== $id) {
            //     $msg .= PHP_EOL . '  非法数据！';
            //     $msg .= PHP_EOL . str_repeat('-', 80);
            //     \Think\Log::write($msg, 'INFO');

            //     $this->error('非法数据！', U('/posts'), 5);
            // }

            $Post->title = I('title');
            $Post->content = I('content');
            $Post->updated_at = date('Y-m-d H:i:s.u');
            $result = $Post->save();

            $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

            if ($result !== false) {
                $this->success('文章保存成功！', U('/posts'), 3);
            } else {
                $this->error('文章保存失败！', U('/posts'), 5);
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }

    public function delete($id)
    {
        if (!IS_POST) {
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::delete():'
            . PHP_EOL . '  $id = ' . $id;

        try {
            $Post = D('Post');
            $post = $Post->find($id);

            $msg .= PHP_EOL . '  $post = ' . print_r($post, true);

            if (!$post) {
                $msg .= PHP_EOL . '  文章不存在！';

                $this->error('文章不存在！', U('/posts'), 5);
            }

            $result = $Post->delete();

            $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

            if ($result !== false) {
                $this->success('文章删除成功！', U('/posts'), 3);
            } else {
                $this->error('文章删除失败！', U('/posts'), 5);
            }
        } catch (Exception $e) {
            $msg .= PHP_EOL . 'error: ' . $e->getMessage();
            throw $e;
        } finally {
            $msg .= PHP_EOL . str_repeat('-', 80);
            \Think\Log::write($msg, 'INFO');
        }
    }
}
