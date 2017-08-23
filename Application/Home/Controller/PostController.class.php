<?php
namespace Home\Controller;

use Think\Controller;
use Think\Model;

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
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/posts'));
            return;
        }

        if (IS_GET) {
            $this->display();
        } elseif (IS_POST) {
            $this->addCore();
        }
    }

    public function add()
    {
        if (IS_POST) {
            $this->addCore();
        }
    }

    public function addCore()
    {
        $msg = PHP_EOL . 'Home\Controller\PostController::addCore():';

        try {
            $Post = D('Post');

            $newPost = $Post->create();

            $msg .= PHP_EOL . '  $newPost = ' . print_r($newPost, true);

            if (!$newPost) {
                $msg .= PHP_EOL . '  validation error: ' . $Post->getError();

                $data = [
                    'post' => I('post.'),
                    'validationError' => $Post->getError(),
                ];
                $this->assign($data);

                // $msg .= PHP_EOL . '  $data = ' . print_r($data, true)
                //     . PHP_EOL . str_repeat('-', 80);
                // \Think\Log::write($msg, 'DEBUG');

                $this->display();
            } else {
                // $msg .= PHP_EOL . '  created $Post:'
                //     . PHP_EOL . '  $Post->title = ' . $Post->title
                //     . PHP_EOL . '  $Post->content = ' . $Post->content;

                // $Post->created_at = date('Y-m-d H:i:s.u');
                $result = $Post->add();

                $msg .= PHP_EOL . '  $result = ' . print_r($result, true);

                if ($result !== false) {
                    $this->success('文章保存成功！', U('/posts'), 3);
                } else {
                    $this->error('文章保存失败！', U('/posts'), 5);
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
        if (!IS_GET && !IS_POST) {
            $this->redirect(U('/posts'));
            return;
        }

        $msg = PHP_EOL . 'Home\Controller\PostController::update():'
            . PHP_EOL . '  $id = ' . $id
            . PHP_EOL . '  REQUEST_METHOD = ' . REQUEST_METHOD;

        try {
            $Post = D('Post');
            $oldPost = $Post->find($id);

            $msg .= PHP_EOL . '  $oldPost = ' . print_r($oldPost, true);

            if (!$oldPost) {
                $msg .= PHP_EOL . '  文章不存在！';

                $this->error('文章不存在！', U('/posts'), 5);
            }

            if (IS_GET) {
                $this->assign('post', $oldPost);
                $this->display();
            } elseif (IS_POST) {
                // $Post->title = I('title');
                // $Post->content = I('content');
                // $Post->updated_at = date('Y-m-d H:i:s.u');

                $updatedPost = $Post->create();

                $msg .= PHP_EOL . '  $updatedPost = ' . print_r($updatedPost, true);

                if (!$updatedPost) {
                    $msg .= PHP_EOL . '  validation error: ' . $Post->getError();

                    $data = [
                        'post' => I('post.'),
                        'validationError' => $Post->getError(),
                    ];
                    $this->assign($data);

                    $this->display();
                } else {
                    $msg .= PHP_EOL . '  validation passed';

                    $result = $Post->save();

                    $msg .= PHP_EOL . '  $result = ' . print_r($result, true);
                    $msg .= PHP_EOL . str_repeat('-', 80);
                    \Think\Log::write($msg, 'INFO');

                    if ($result !== false) {
                        $this->success('文章保存成功！', U('/posts'), 3);
                    } else {
                        $this->error('文章保存失败！', U('/posts'), 5);
                    }
                }
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
